<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentMeeting extends Model
{
    protected $fillable = [
        'student_id',
        'parent_id',
        'teacher_id',
        'room',
        'floor',
        'meeting_time',
        'note',
        'status',
        'created_by_user_id',
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
