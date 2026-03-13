<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ParentMeetingsRequest;
use App\Models\ParentMeeting;
use App\Models\Schedule;
use App\Models\SubjectTeacher;
use App\Models\User;

class ParentController extends Controller
{
    public function children()
    {
        $user = request()->user();

        $studentsQuery = User::query()
            ->with('klas:id,title')
            ->where('role', 'student');

        if ($user->role === 'parent') {
            $studentsQuery->where(function ($query) use ($user) {
                $query->where('parent_father_id', $user->id)
                    ->orWhere('parent_mother_id', $user->id);
            });
        }

        $items = $studentsQuery
            ->orderBy('name')
            ->get(['id', 'name', 'klas_id'])
            ->map(fn ($student) => [
                'id' => (string) $student->id,
                'name' => (string) $student->name,
                'class_name' => optional($student->klas)->title ?? '',
            ])
            ->values();

        return response()->json(['items' => $items]);
    }

    public function teachers(ParentMeetingsRequest $request)
    {
        $user = $request->user();
        $requestedStudentId = $request->filled('student_id') ? (int) $request->input('student_id') : null;
        $allowedStudentIds = $this->resolveParentStudentIds($user->id);

        if ($user->role === 'parent' && $allowedStudentIds->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $studentId = $requestedStudentId;
        if ($studentId === null) {
            $studentId = $allowedStudentIds->first();
        }

        if ($user->role === 'parent' && $studentId && ! $allowedStudentIds->contains($studentId)) {
            return response()->json([
                'message' => 'Student does not belong to current parent.',
                'status_code' => 403,
                'error_code' => 'PARENT_STUDENT_FORBIDDEN',
            ], 403);
        }

        if (! $studentId) {
            return response()->json(['items' => []]);
        }

        $student = User::query()
            ->where('id', $studentId)
            ->where('role', 'student')
            ->first();

        if (! $student || ! $student->klas_id) {
            return response()->json(['items' => []]);
        }

        $items = Schedule::query()
            ->with(['subjectTeacher.teacher:id,name', 'subjectTeacher.subject:id,name'])
            ->where('klas_id', $student->klas_id)
            ->whereNotNull('subject_teacher_id')
            ->orderBy('day_of_week')
            ->orderBy('period')
            ->get()
            ->map(function ($schedule) {
                $assignment = $schedule->subjectTeacher;
                if (! $assignment || ! $assignment->teacher || ! $assignment->subject) {
                    return null;
                }

                return [
                    'teacher_name' => (string) $assignment->teacher->name,
                    'subject' => (string) $assignment->subject->name,
                    'room' => (string) ($assignment->room ?? ''),
                    'floor' => (int) ($assignment->floor ?? 0),
                    'map_x' => (float) ($assignment->map_x ?? 0),
                    'map_y' => (float) ($assignment->map_y ?? 0),
                ];
            })
            ->filter()
            ->unique(fn ($item) => implode('|', [
                $item['teacher_name'],
                $item['subject'],
                $item['room'],
                $item['floor'],
            ]))
            ->values();

        return response()->json(['items' => $items]);
    }

    public function meetings(ParentMeetingsRequest $request)
    {
        $user = $request->user();
        $studentId = $request->filled('student_id') ? (int) $request->input('student_id') : null;

        $query = ParentMeeting::query()->with('teacher:id,name');

        if ($user->role === 'parent') {
            if (! $studentId) {
                $studentId = User::query()
                    ->where('role', 'student')
                    ->where(function ($subQuery) use ($user) {
                        $subQuery->where('parent_father_id', $user->id)
                            ->orWhere('parent_mother_id', $user->id);
                    })
                    ->value('id');
            }

            $query->where(function ($subQuery) use ($user, $studentId) {
                $subQuery->where('parent_id', $user->id);

                if ($studentId) {
                    $subQuery->orWhere('student_id', $studentId);
                }
            });
        } elseif ($user->role === 'teacher') {
            $query->where('teacher_id', $user->id);
            if ($studentId) {
                $query->where('student_id', $studentId);
            }
        } elseif ($studentId) {
            $query->where('student_id', $studentId);
        }

        $items = $query
            ->orderBy('meeting_time')
            ->get()
            ->map(function ($meeting) {
                $location = SubjectTeacher::query()
                    ->where('teacher_id', $meeting->teacher_id)
                    ->when($meeting->room, fn ($q) => $q->where('room', $meeting->room))
                    ->first();

                return [
                    'id' => (string) $meeting->id,
                    'teacher' => optional($meeting->teacher)->name ?? '',
                    'room' => $meeting->room,
                    'floor' => (int) $meeting->floor,
                    'time' => optional($meeting->meeting_time)?->toISOString(),
                    'note' => $meeting->note,
                    'map_x' => (float) ($location->map_x ?? 0),
                    'map_y' => (float) ($location->map_y ?? 0),
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    private function resolveParentStudentIds(int $parentId)
    {
        return User::query()
            ->where('role', 'student')
            ->where(function ($query) use ($parentId) {
                $query->where('parent_father_id', $parentId)
                    ->orWhere('parent_mother_id', $parentId);
            })
            ->pluck('id');
    }
}
