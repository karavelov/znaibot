<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'required_points',
    ];
    

    // един ранг може да има много потребители
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
