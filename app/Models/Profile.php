<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id' ,
        'goverment_id_number',
        'address',
        'city',
        'language',
        'profile_picture_url',  
    ] ;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
