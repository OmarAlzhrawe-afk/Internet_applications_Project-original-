<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint_attachment extends Model
{
    protected $table = "complaint_attachments";
    protected $fillable = [
        'id',
        'file_path',
        'file_type',
        'complaint_id',
        'description',
    ];
    // Relations
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, "complaint_id", "id");
    }
}
