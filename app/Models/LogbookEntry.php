<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogbookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_application_id',
        'log_date',
        'check_in_time',
        'check_out_time',
        'activity_description',
        'tools_used',
        'competencies',
        'evidence_path',
        'status',
        'industry_comment',
        'validated_at',
    ];

    protected $casts = [
        'log_date'     => 'date',
        'check_in_time'=> 'datetime:H:i',
        'check_out_time'=> 'datetime:H:i',
        'validated_at' => 'datetime',
    ];

    public const STATUS_WAITING = 'waiting_validation';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public function application()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }

    public function internshipApplication()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }
}
