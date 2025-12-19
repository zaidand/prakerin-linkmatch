<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndustryAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_application_id',
        'discipline',
        'technical_skill',
        'teamwork',
        'communication',
        'responsibility',
        'overall_score',
        'notes',
    ];

    public function application()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }
}
