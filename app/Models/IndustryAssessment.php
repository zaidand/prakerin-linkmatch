<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndustryAssessment extends Model
{
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

    public function internshipApplication(): BelongsTo
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }
}
