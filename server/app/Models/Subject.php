<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name'];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(SubjectTeacher::class);
    }
}
