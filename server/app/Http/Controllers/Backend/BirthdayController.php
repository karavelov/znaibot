<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'today');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $today = Carbon::today();

        switch ($filter) {
            case 'tomorrow':
                $start = $today->copy()->addDay();
                $end   = $today->copy()->addDay();
                $label = 'Утре';
                break;
            case 'week':
                $start = $today->copy()->addDay();
                $end   = $today->copy()->addDays(7);
                $label = 'Следващите 7 дни';
                break;
            case 'month':
                $start = $today->copy()->startOfMonth();
                $end   = $today->copy()->endOfMonth();
                $label = 'Този месец';
                break;
            case 'custom':
                $start = $dateFrom ? Carbon::parse($dateFrom) : $today->copy();
                $end   = $dateTo   ? Carbon::parse($dateTo)   : $today->copy();
                $label = 'Период: ' . $start->format('d.m.Y') . ' – ' . $end->format('d.m.Y');
                break;
            default: // today
                $start = $today->copy();
                $end   = $today->copy();
                $label = 'Днес';
                break;
        }

        $users = $this->getBirthdays($start, $end);

        return view('admin.birthdays.index', compact('users', 'filter', 'dateFrom', 'dateTo', 'label', 'today'));
    }

    private function getBirthdays(Carbon $start, Carbon $end): \Illuminate\Support\Collection
    {
        // Get all users with birth_date set
        $query = User::whereNotNull('birth_date')
            ->with(['klas'])
            ->get();

        // Filter by month/day range (ignore year)
        $filtered = $query->filter(function ($user) use ($start, $end) {
            $birth = Carbon::parse($user->birth_date);

            // Build candidates for current year and possibly next year (for wrap-around Dec→Jan)
            $thisYear  = $birth->copy()->year(Carbon::now()->year);
            $nextYear  = $birth->copy()->year(Carbon::now()->year + 1);

            return ($thisYear->between($start, $end) || $nextYear->between($start, $end));
        });

        // Sort: teachers first → students (by klas title asc) → others
        $roleOrder = ['teacher' => 0, 'student' => 1];

        $sorted = $filtered->sort(function ($a, $b) use ($roleOrder) {
            $ra = $roleOrder[$a->role] ?? 2;
            $rb = $roleOrder[$b->role] ?? 2;

            if ($ra !== $rb) {
                return $ra <=> $rb;
            }

            // Same role group
            if ($a->role === 'student' && $b->role === 'student') {
                $ka = $a->klas->title ?? 'zzz';
                $kb = $b->klas->title ?? 'zzz';

                // Natural sort for class titles like "1А", "2Б", "10В"
                if ($ka !== $kb) {
                    return strnatcasecmp($ka, $kb);
                }
            }

            return strcmp($a->name, $b->name);
        });

        // Add computed age and upcoming birthday date
        $now = Carbon::now()->year;
        return $sorted->map(function ($user) use ($now) {
            $birth = Carbon::parse($user->birth_date);
            $user->age        = $now - $birth->year;
            $user->birth_month_day = $birth->format('d.m');
            return $user;
        })->values();
    }
}
