<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['klas_id', 'semester', 'day_of_week', 'period', 'subject_teacher_id'];

    public function klas()
    {
        return $this->belongsTo(Klas::class);
    }

    public function subjectTeacher()
    {
        return $this->belongsTo(SubjectTeacher::class);
    }
}
