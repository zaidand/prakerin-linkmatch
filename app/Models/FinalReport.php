<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_application_id',
        'file_path',
        'summary',
        'status',
        'teacher_score',
        'teacher_comment',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at'    => 'datetime',
    ];

    public const STATUS_WAITING  = 'waiting_teacher';
    public const STATUS_REVISION = 'revision';
    public const STATUS_GRADED   = 'graded';

    public function application()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }
}
