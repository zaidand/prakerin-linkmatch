<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndustryQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'start_date',
        'end_date',
        'max_students',
        'criteria',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function applications()
    {
    return $this->hasMany(InternshipApplication::class);
    }
}
