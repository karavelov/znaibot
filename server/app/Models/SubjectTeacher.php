<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    protected $table = 'subject_teacher';

    protected $fillable = ['subject_id', 'teacher_id', 'room', 'floor', 'map_x', 'map_y'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
