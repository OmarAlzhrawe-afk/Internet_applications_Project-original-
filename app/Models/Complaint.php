<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Complaint extends Model
{
    use LogsActivity;
    use HasFactory;
    protected $table = "complaints";
    protected $fillable = [
        'title',
        'description',
        'type', // [ "خدمة",'سلوك' , "بنية تحتية"]
        'priority', //['high', 'low', 'medium']
        'status', // ['new', 'in_review', 'in_progress', 'awaiting_info', 'resolved', 'rejected', 'closed']
        'latitude',
        'longitude',
        'address_text',
        'is_locked',
        'citizen_id',
        'agency_id',
        'employee_id',
        'created_at'
    ];
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'citizen_id');
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
