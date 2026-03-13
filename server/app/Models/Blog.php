<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(BlogSubCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }


    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
