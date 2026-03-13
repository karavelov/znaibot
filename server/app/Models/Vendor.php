<?php

namespace App\Models;

class Vendor extends User
{
    protected static function booted(): void
    {
        static::addGlobalScope('vendor_role', function ($query) {
            $query->where('role', 'vendor');
        });
    }
}
