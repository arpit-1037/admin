<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Address.php
class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address_line',
        'city',
        'state',
        'postal_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
