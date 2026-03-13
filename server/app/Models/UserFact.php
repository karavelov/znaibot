<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFact extends Model
{
    protected $fillable = ['text', 'userid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
