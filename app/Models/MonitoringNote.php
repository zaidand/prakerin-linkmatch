<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_application_id',
        'teacher_id',
        'note_date',
        'note',
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    public function application()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
