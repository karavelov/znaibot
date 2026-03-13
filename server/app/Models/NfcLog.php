<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NfcLog extends Model
{
    protected $fillable = ['user_id', 'nfc_id', 'nfc_reader_id', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nfcReader()
    {
        return $this->belongsTo(NfcReader::class);
    }
}
