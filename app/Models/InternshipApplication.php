<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\LogbookEntry;
use App\Models\MonitoringNote;

class InternshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'industry_id',
        'industry_quota_id',
        'requested_quota_id',
        'status',
        'gpa',
        'interest',
        'additional_info',
        'teacher_note',
        'admin_note',
        'industry_note',
        'teacher_verified_at',
        'admin_assigned_at',
        'industry_confirmed_at',
    ];

    protected $casts = [
        'gpa'                  => 'float',
        'teacher_verified_at'  => 'datetime',
        'admin_assigned_at'    => 'datetime',
        'industry_confirmed_at'=> 'datetime',
    ];

    // Status constants (supaya rapi di controller)
    public const STATUS_WAITING_TEACHER = 'waiting_teacher_verification';
    public const STATUS_APPROVED_BY_TEACHER = 'approved_by_teacher';
    public const STATUS_ASSIGNED_BY_ADMIN = 'assigned_by_admin';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function quota()
    {
        return $this->belongsTo(IndustryQuota::class, 'industry_quota_id');
    }

    public function requestedQuota()
    {
        return $this->belongsTo(IndustryQuota::class, 'requested_quota_id');
    }

    public function getEffectiveQuotaAttribute(): ?IndustryQuota
    {
        // Kuota efektif = kuota yang ditetapkan admin (industry_quota_id) jika ada,
        // kalau belum, pakai kuota yang dipilih siswa saat pengajuan (requested_quota_id).
        return $this->quota ?? $this->requestedQuota;
    }

    public function logbooks()
    {
    return $this->hasMany(LogbookEntry::class);
    }

    public function monitoringNotes()
    {
    return $this->hasMany(MonitoringNote::class);
    }

    public function logbookEntries()
    {
        return $this->hasMany(LogbookEntry::class, 'internship_application_id');
    }

    public function finalReport()
    {
    return $this->hasOne(FinalReport::class);
    }

    public function industryAssessment()
    {
    return $this->hasOne(IndustryAssessment::class);
    }

    public function finalGrade()
    {
    return $this->hasOne(FinalGrade::class);
    }
}
