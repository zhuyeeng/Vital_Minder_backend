<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'name',
        'date',
        'time',
        'type',
        'blood_type',
        'details',
        'status',
        'reason',
        'paramedic_id',
        'doctor_id',
        // 'medical_summary_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function paramedic()
    {
        return $this->belongsTo(Paramedic::class, 'paramedic_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // public function medicalSummary()
    // {
    //     return $this->belongsTo(MedicalSummary::class, 'medical_summary_id');
    // }
}
