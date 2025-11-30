<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'code',
        'identifier',
        'purpose',
        'is_used',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
