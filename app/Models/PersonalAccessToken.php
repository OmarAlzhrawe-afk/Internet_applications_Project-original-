<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    protected $fillable = [
        'name',
        'tokenable_id',
        'tokenable_type',
        'updated_at',
        'created_at',
    ];
}
