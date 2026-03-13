<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klas extends Model
{
    protected $table = 'klasses';

    protected $fillable = ['title'];

    // Учениците в този клас (pivot)
    public function users()
    {
        return $this->belongsToMany(User::class, 'klas_users');
    }

    // Учениците чрез FK в users таблицата
    public function students()
    {
        return $this->hasMany(User::class, 'klas_id');
    }

    // Класен ръководител (учител)
    public function homeroomTeacher()
    {
        return $this->hasOne(User::class, 'homeroom_klas_id');
    }

    public function semesters()
    {
        return $this->hasMany(KlasSemester::class);
    }

    public function semester1()
    {
        return $this->hasOne(KlasSemester::class)->where('semester', 1);
    }

    public function semester2()
    {
        return $this->hasOne(KlasSemester::class)->where('semester', 2);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
