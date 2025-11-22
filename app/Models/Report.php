<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = "reports";
    protected $fillable = [
        'id',
        'file_path',
        'status',
        'report_type',
        'created_by',
    ];
    public function created_by()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
