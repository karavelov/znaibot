<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasSemester extends Model
{
    protected $table = 'klas_semesters';

    protected $fillable = ['klas_id', 'semester', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function klas()
    {
        return $this->belongsTo(Klas::class);
    }
}
