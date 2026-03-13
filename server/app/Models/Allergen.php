<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    protected $fillable = ['name', 'description', 'color'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_allergen')
                    ->withPivot('notes')
                    ->withTimestamps();
    }
}
