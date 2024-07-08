<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'paramedic_id',
        'doctor_id',
        'date',
        'time',
        'details',
        'location',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function paramedic()
    {
        return $this->belongsTo(Paramedic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
