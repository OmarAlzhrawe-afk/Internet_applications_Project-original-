<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint_log extends Model
{
    protected $table = "complaint_logs";
    protected $fillable = [
        'id',
        'nots',
        'complaint_id',
        'user_id',
    ];
    // Relations
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, "complaint_id", "id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
