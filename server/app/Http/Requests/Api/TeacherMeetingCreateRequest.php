<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TeacherMeetingCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teacher' => ['nullable', 'string', 'max:255'],
            'room' => ['required', 'string', 'max:64'],
            'floor' => ['required', 'integer', 'between:1,4'],
            'time' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'student_id' => ['nullable', 'integer', 'exists:users,id'],
            'parent_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
