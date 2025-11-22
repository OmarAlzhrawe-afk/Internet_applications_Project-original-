<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentAgencie extends Model
{
    protected $table = 'government_agencies';
    protected $fillable = [
        'name',
        'description',
        'address',
        'contact_email',
        'contact_phone',
    ];
    
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'agency_id');
    }
    public function supervisor() {}
    public function employees() {}
}
