<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = "complaints";
    protected $fillable = [
        'title',
        'description',
        'type', // [ "خدمة",'سلوك' , "بنية تحتية"]
        'priority', //['high', 'low', 'medium']
        'status', // ['new', 'in_review', 'in_progress', 'awaiting_info', 'resolved', 'rejected', 'closed']
        'latitude',
        'longitude',
        'text_address',
        'is_locked',
        'citizen_id',
        'agency_id',
        'employee_id',
        'created_at'
    ];
    public function employee()
    {
        return $this->belongsTo(User::class, 'locked_by_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function attachments()
    {
        return $this->hasMany(Complaint_attachment::class, "complaint_id", "id");
    }
    public function comments()
    {
        return $this->hasMany(Complaint_comment::class, "complaint_id", "id");
    }
    public function logs()
    {
        return $this->hasMany(Complaint_log::class, "complaint_id", "id");
    }
}
