<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinalGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_application_id',
        'industry_score',
        'report_score',
        'attendance_score',
        'weight_industry',
        'weight_report',
        'weight_attendance',
        'final_score',
        'grade_letter',
        'locked',
    ];

    protected $casts = [
        'locked' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }
}
