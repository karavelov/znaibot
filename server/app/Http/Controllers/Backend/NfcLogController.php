<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\NfcLogDataTable;
use App\Http\Controllers\Controller;
use App\Models\NfcLog;
use App\Models\NfcReader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class NfcLogController extends Controller
{
    public function index(NfcLogDataTable $dataTable)
    {
        $readers = NfcReader::orderBy('title')->get();
        return $dataTable->render('admin.nfc-logs.index', compact('readers'));
    }

    // Dashboard — кой е в училище сега
    public function presence()
    {
        // Последното сканиране на врата (in/out) за всеки потребител днес
        $latestDoorIds = NfcLog::select('nfc_logs.user_id', DB::raw('MAX(nfc_logs.id) as max_id'))
            ->join('nfc_readers', 'nfc_logs.nfc_reader_id', '=', 'nfc_readers.id')
            ->whereNotNull('nfc_logs.user_id')
            ->whereIn('nfc_readers.type', ['door_in', 'door_out'])
            ->whereDate('nfc_logs.read_at', today())
            ->groupBy('nfc_logs.user_id');

        // Потребители, чието последно сканиране е от четец тип 'door_in'
        $presentLogs = NfcLog::joinSub($latestDoorIds, 'latest', function ($join) {
            $join->on('nfc_logs.id', '=', 'latest.max_id');
        })
            ->join('nfc_readers', 'nfc_logs.nfc_reader_id', '=', 'nfc_readers.id')
            ->where('nfc_readers.type', 'door_in')
            ->with(['user.klas', 'nfcReader'])
            ->orderBy('nfc_logs.read_at', 'desc')
            ->get();

        // Взаимодействия с Знайбот днес
        $robotLogs = NfcLog::join('nfc_readers', 'nfc_logs.nfc_reader_id', '=', 'nfc_readers.id')
            ->where('nfc_readers.type', 'robot')
            ->whereDate('nfc_logs.read_at', today())
            ->whereNotNull('nfc_logs.user_id')
            ->with('user')
            ->select('nfc_logs.user_id', DB::raw('COUNT(*) as count'), DB::raw('MAX(nfc_logs.read_at) as last_at'))
            ->groupBy('nfc_logs.user_id')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.nfc-logs.presence', compact('presentLogs', 'robotLogs'));
    }

    // Статистика за конкретен потребител
    public function userStats(Request $request, User $user)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : null;
        $dateTo   = $request->filled('date_to')   ? $request->date_to   : null;

        // Ако само едната дата е попълнена — третираме като единичен ден
        if ($dateFrom && !$dateTo) $dateTo   = $dateFrom;
        if ($dateTo   && !$dateFrom) $dateFrom = $dateTo;

        $doorQuery = NfcLog::where('nfc_logs.user_id', $user->id)
            ->join('nfc_readers', 'nfc_logs.nfc_reader_id', '=', 'nfc_readers.id')
            ->whereIn('nfc_readers.type', ['door_in', 'door_out'])
            ->select('nfc_logs.*', 'nfc_readers.type as reader_type', 'nfc_readers.title as reader_title')
            ->orderBy('nfc_logs.read_at');

        if ($dateFrom) $doorQuery->whereDate('nfc_logs.read_at', '>=', $dateFrom);
        if ($dateTo)   $doorQuery->whereDate('nfc_logs.read_at', '<=', $dateTo);

        $doorLogs = $doorQuery->get();

        // Групиране по дата и изчисляване на сесии
        $days = [];
        foreach ($doorLogs->groupBy(fn($l) => $l->read_at->toDateString()) as $date => $dateLogs) {
            $sessions  = [];
            $currentIn = null;

            foreach ($dateLogs as $log) {
                if ($log->reader_type === 'door_in' && $currentIn === null) {
                    $currentIn = $log;
                } elseif ($log->reader_type === 'door_out' && $currentIn !== null) {
                    $sessions[] = [
                        'in'         => $currentIn->read_at,
                        'out'        => $log->read_at,
                        'minutes'    => $currentIn->read_at->diffInMinutes($log->read_at),
                        'reader_in'  => $currentIn->reader_title,
                        'reader_out' => $log->reader_title,
                    ];
                    $currentIn = null;
                }
            }

            if ($currentIn !== null) {
                $sessions[] = [
                    'in'         => $currentIn->read_at,
                    'out'        => null,
                    'minutes'    => null,
                    'reader_in'  => $currentIn->reader_title,
                    'reader_out' => null,
                ];
            }

            $days[$date] = [
                'sessions'     => $sessions,
                'totalMinutes' => collect($sessions)->whereNotNull('minutes')->sum('minutes'),
            ];
        }

        krsort($days);

        // Знайбот взаимодействия по дата
        $robotQuery = NfcLog::where('nfc_logs.user_id', $user->id)
            ->join('nfc_readers', 'nfc_logs.nfc_reader_id', '=', 'nfc_readers.id')
            ->where('nfc_readers.type', 'robot')
            ->selectRaw('DATE(nfc_logs.read_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(nfc_logs.read_at)');

        if ($dateFrom) $robotQuery->whereDate('nfc_logs.read_at', '>=', $dateFrom);
        if ($dateTo)   $robotQuery->whereDate('nfc_logs.read_at', '<=', $dateTo);

        $robotByDate = $robotQuery->pluck('count', 'date');

        // Обобщени данни
        $daysWithSessions = collect($days)->filter(fn($d) => count($d['sessions']) > 0);
        $totalDays        = $daysWithSessions->count();
        $totalMinutesAll  = $daysWithSessions->sum('totalMinutes');
        $avgMinutes       = $totalDays > 0 ? (int) round($totalMinutesAll / $totalDays) : 0;
        $totalRobot       = $robotByDate->sum();

        return view('admin.nfc-logs.user-stats', compact(
            'user', 'days', 'robotByDate',
            'totalDays', 'totalMinutesAll', 'avgMinutes', 'totalRobot',
            'dateFrom', 'dateTo'
        ));
    }

    // Статистика на закъснения
    public function lateStats(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : today()->toDateString();
        $dateTo   = $request->filled('date_to')   ? $request->date_to   : today()->toDateString();
        $cutoff   = $request->input('cutoff', '08:00');

        if ($dateFrom > $dateTo) [$dateFrom, $dateTo] = [$dateTo, $dateFrom];

        // Първо влизане (door_in) на всеки потребител за всеки ден
        $rows = DB::table('nfc_logs')
            ->join('nfc_readers', 'nfc_logs.nfc_reader_id', '=', 'nfc_readers.id')
            ->join('users', 'nfc_logs.user_id', '=', 'users.id')
            ->leftJoin('klasses', 'users.klas_id', '=', 'klasses.id')
            ->where('nfc_readers.type', 'door_in')
            ->whereNotNull('nfc_logs.user_id')
            ->whereDate('nfc_logs.read_at', '>=', $dateFrom)
            ->whereDate('nfc_logs.read_at', '<=', $dateTo)
            ->select(
                'nfc_logs.user_id',
                'users.name as user_name',
                'users.image as user_image',
                'users.role as user_role',
                'klasses.title as klas_title',
                DB::raw('DATE(nfc_logs.read_at) as date'),
                DB::raw('MIN(nfc_logs.read_at) as first_arrival')
            )
            ->groupBy(
                'nfc_logs.user_id',
                DB::raw('DATE(nfc_logs.read_at)'),
                'users.name', 'users.image', 'users.role', 'klasses.title'
            )
            ->havingRaw('TIME(MIN(nfc_logs.read_at)) > ?', [$cutoff . ':00'])
            ->orderBy('date', 'desc')
            ->orderBy('first_arrival')
            ->get()
            ->map(function ($row) use ($cutoff) {
                $arrival        = \Carbon\Carbon::parse($row->first_arrival);
                $cutoffDt       = \Carbon\Carbon::parse($row->date . ' ' . $cutoff . ':00');
                $row->minutes_late = $cutoffDt->diffInMinutes($arrival);
                return $row;
            });

        // Групиране по дата
        $byDate = $rows->groupBy('date');

        // Обобщение по потребител
        $byUser = $rows->groupBy('user_id')->map(function ($entries) {
            return (object) [
                'user_id'     => $entries->first()->user_id,
                'user_name'   => $entries->first()->user_name,
                'user_image'  => $entries->first()->user_image,
                'user_role'   => $entries->first()->user_role,
                'klas_title'  => $entries->first()->klas_title,
                'count'       => $entries->count(),
                'avg_minutes' => (int) round($entries->avg('minutes_late')),
                'max_minutes' => $entries->max('minutes_late'),
            ];
        })->sortByDesc('count')->values();

        $totalInstances  = $rows->count();
        $uniqueLateUsers = $byUser->count();

        return view('admin.nfc-logs.late-stats', compact(
            'byDate', 'byUser', 'dateFrom', 'dateTo', 'cutoff',
            'totalInstances', 'uniqueLateUsers'
        ));
    }

    // API endpoint за NFC четците
    public function scan(Request $request)
    {
        $request->validate([
            'nfc_id'        => ['required', 'string'],
            'nfc_reader_id' => ['required', 'integer', 'exists:nfc_readers,id'],
        ]);

        $user = User::where('nfc_id', $request->nfc_id)->first();

        $log = NfcLog::create([
            'user_id'       => $user?->id,
            'nfc_id'        => $request->nfc_id,
            'nfc_reader_id' => $request->nfc_reader_id,
            'read_at'       => now(),
        ]);

        return response()->json([
            'status'  => 'ok',
            'log_id'  => $log->id,
            'user'    => $user ? [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ] : null,
            'unknown' => $user === null,
        ]);
    }
}
