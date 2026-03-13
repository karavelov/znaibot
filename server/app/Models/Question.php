<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions_new';

    public $timestamps = false;

    protected $fillable = [
        'question',
        'klas',
        'points',
    ];
}