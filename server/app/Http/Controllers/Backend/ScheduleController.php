<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Klas;
use App\Models\KlasSemester;
use App\Models\Schedule;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    const PERIODS = 8;
    const DAYS = [
        1 => 'Понеделник',
        2 => 'Вторник',
        3 => 'Сряда',
        4 => 'Четвъртък',
        5 => 'Петък',
    ];

    public function index()
    {
        $klasses = Klas::with(['semester1', 'semester2'])->orderBy('title')->get();
        return view('admin.schedule.index', compact('klasses'));
    }

    public function edit(string $klasId, int $semester)
    {
        $klas = Klas::findOrFail($klasId);

        $semesterDates = KlasSemester::where('klas_id', $klasId)
            ->where('semester', $semester)
            ->first();

        $subjectTeachers = SubjectTeacher::with(['subject', 'teacher'])
            ->get()
            ->sortBy(fn($st) => $st->subject->name . $st->teacher->name);

        // Индексирано по "ден_час" за бърз достъп в blade
        $scheduleData = Schedule::where('klas_id', $klasId)
            ->where('semester', $semester)
            ->get()
            ->keyBy(fn($s) => $s->day_of_week . '_' . $s->period);

        $days    = self::DAYS;
        $periods = self::PERIODS;

        return view('admin.schedule.edit', compact(
            'klas', 'semester', 'semesterDates',
            'subjectTeachers', 'scheduleData', 'days', 'periods'
        ));
    }

    public function update(Request $request, string $klasId, int $semester)
    {
        // Изтрий старото разписание за този клас+срок
        Schedule::where('klas_id', $klasId)->where('semester', $semester)->delete();

        // Запиши новото
        if ($request->has('schedule')) {
            $inserts = [];
            $now     = now();

            foreach ($request->schedule as $day => $periods) {
                foreach ($periods as $period => $subjectTeacherId) {
                    if (!$subjectTeacherId) continue;

                    $inserts[] = [
                        'klas_id'            => $klasId,
                        'semester'           => $semester,
                        'day_of_week'        => (int) $day,
                        'period'             => (int) $period,
                        'subject_teacher_id' => (int) $subjectTeacherId,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];
                }
            }

            if (!empty($inserts)) {
                Schedule::insert($inserts);
            }
        }

        toastr('Разписанието е запазено успешно!', 'success', 'Успешно!');
        return redirect()->back();
    }
}
