<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NfcReader extends Model
{
    protected $table = 'nfc_readers';

    protected $fillable = ['title', 'type'];

    public static array $typeLabels = [
        'door_in'  => 'Вход (врата)',
        'door_out' => 'Изход (врата)',
        'robot'    => 'Знайбот',
        'other'    => 'Друг',
    ];

    public function logs()
    {
        return $this->hasMany(NfcLog::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type] ?? $this->type;
    }
}
