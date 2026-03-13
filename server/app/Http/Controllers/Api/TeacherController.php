<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScheduleRequest;
use App\Http\Requests\Api\TeacherMeetingCreateRequest;
use App\Models\ParentMeeting;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Carbon;

class TeacherController extends Controller
{
    public function map()
    {
        $items = Schedule::query()
            ->with(['subjectTeacher.teacher:id,name', 'subjectTeacher.subject:id,name'])
            ->whereNotNull('subject_teacher_id')
            ->get()
            ->map(function ($schedule) {
                $assignment = $schedule->subjectTeacher;
                if (! $assignment || ! $assignment->teacher || ! $assignment->subject) {
                    return null;
                }

                return [
                    'teacher_name' => $assignment->teacher->name,
                    'subject' => $assignment->subject->name,
                    'room' => $assignment->room ?? '',
                    'floor' => (int) ($assignment->floor ?? 0),
                    'map_x' => (float) ($assignment->map_x ?? 0),
                    'map_y' => (float) ($assignment->map_y ?? 0),
                ];
            })
            ->filter()
            ->values();

        return response()->json(['items' => $items]);
    }

    public function schedule(ScheduleRequest $request)
    {
        $authUser = $request->user();
        $studentId = $request->filled('student_id') ? (int) $request->input('student_id') : null;

        if ($studentId === null && $authUser->role === 'student') {
            $studentId = $authUser->id;
        }

        if ($studentId === null && $authUser->role === 'parent') {
            $studentId = User::query()
                ->where('role', 'student')
                ->where(function ($query) use ($authUser) {
                    $query->where('parent_father_id', $authUser->id)
                        ->orWhere('parent_mother_id', $authUser->id);
                })
                ->value('id');
        }

        if ($authUser->role === 'parent' && $studentId !== null) {
            $belongsToParent = User::query()
                ->where('id', $studentId)
                ->where('role', 'student')
                ->where(function ($query) use ($authUser) {
                    $query->where('parent_father_id', $authUser->id)
                        ->orWhere('parent_mother_id', $authUser->id);
                })
                ->exists();

            if (! $belongsToParent) {
                return response()->json([
                    'message' => 'Student does not belong to current parent.',
                    'status_code' => 403,
                    'error_code' => 'PARENT_STUDENT_FORBIDDEN',
                ], 403);
            }
        }

        $dayMap = [
            1 => 'Понеделник',
            2 => 'Вторник',
            3 => 'Сряда',
            4 => 'Четвъртък',
            5 => 'Петък',
        ];

        $periodMap = [
            1 => '08:00 - 08:40',
            2 => '08:50 - 09:30',
            3 => '09:40 - 10:20',
            4 => '10:30 - 11:10',
            5 => '11:20 - 12:00',
            6 => '12:10 - 12:50',
            7 => '13:00 - 13:40',
            8 => '13:50 - 14:30',
        ];

        if ($studentId === null && $authUser->role === 'teacher') {
            $items = Schedule::query()
                ->with(['subjectTeacher.subject:id,name', 'subjectTeacher.teacher:id,name'])
                ->whereNotNull('subject_teacher_id')
                ->whereHas('subjectTeacher', fn ($query) => $query->where('teacher_id', $authUser->id))
                ->orderBy('day_of_week')
                ->orderBy('period')
                ->get()
                ->map(function ($schedule) use ($dayMap, $periodMap) {
                    $assignment = $schedule->subjectTeacher;

                    return [
                        'day' => $dayMap[$schedule->day_of_week] ?? (string) $schedule->day_of_week,
                        'time' => $periodMap[$schedule->period] ?? (string) $schedule->period,
                        'subject' => optional(optional($assignment)->subject)->name ?? '',
                        'teacher' => optional(optional($assignment)->teacher)->name ?? '',
                        'room' => $assignment->room ?? '',
                    ];
                })
                ->values();

            return response()->json(['items' => $items]);
        }

        if ($studentId === null && $authUser->role === 'parent') {
            return response()->json(['items' => []]);
        }

        if ($studentId === null) {
            return response()->json([
                'message' => 'student_id is required for this role',
                'status_code' => 422,
                'error_code' => 'STUDENT_ID_REQUIRED',
            ], 422);
        }

        $student = User::query()
            ->where('id', $studentId)
            ->where('role', 'student')
            ->first();

        if (! $student || ! $student->klas_id) {
            return response()->json(['items' => []]);
        }

        $items = Schedule::query()
            ->with(['subjectTeacher.subject:id,name', 'subjectTeacher.teacher:id,name'])
            ->where('klas_id', $student->klas_id)
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get()
            ->map(function ($schedule) use ($dayMap, $periodMap) {
                $assignment = $schedule->subjectTeacher;

                return [
                    'day' => $dayMap[$schedule->day_of_week] ?? (string) $schedule->day_of_week,
                    'time' => $periodMap[$schedule->period] ?? (string) $schedule->period,
                    'subject' => optional(optional($assignment)->subject)->name ?? '',
                    'teacher' => optional(optional($assignment)->teacher)->name ?? '',
                    'room' => $assignment->room ?? '',
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    public function students()
    {
        $authUser = request()->user();

        $studentsQuery = User::query()
            ->with('klas:id,title')
            ->where('role', 'student');

        if ($authUser->role === 'teacher') {
            $classIds = Schedule::query()
                ->join('subject_teacher', 'subject_teacher.id', '=', 'schedules.subject_teacher_id')
                ->where('subject_teacher.teacher_id', $authUser->id)
                ->distinct()
                ->pluck('schedules.klas_id');

            $studentsQuery->whereIn('klas_id', $classIds);
        }

        $items = $studentsQuery
            ->orderBy('name')
            ->get(['id', 'name', 'klas_id'])
            ->map(fn ($student) => [
                'id' => (string) $student->id,
                'name' => $student->name,
                'class_name' => optional($student->klas)->title ?? '',
            ])
            ->values();

        return response()->json(['items' => $items]);
    }

    public function createMeeting(TeacherMeetingCreateRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $meeting = ParentMeeting::query()->create([
            'student_id' => $data['student_id'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'teacher_id' => $user->role === 'teacher' ? $user->id : ($data['teacher_id'] ?? $user->id),
            'room' => $data['room'],
            'floor' => $data['floor'],
            'meeting_time' => Carbon::parse($data['time']),
            'note' => $data['note'] ?? null,
            'status' => 'scheduled',
            'created_by_user_id' => $user->id,
        ]);

        return response()->json([
            'id' => (string) $meeting->id,
            'teacher' => $user->name,
            'room' => $meeting->room,
            'floor' => (int) $meeting->floor,
            'time' => optional($meeting->meeting_time)?->toISOString(),
            'note' => $meeting->note,
        ], 201);
    }
}
