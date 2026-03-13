<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolInfoBlock extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
