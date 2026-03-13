<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'members',
        'icon',
        'about',
        'achievements',
        'gallery_id',
        'status',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'club_user')->withTimestamps();
    }
}
