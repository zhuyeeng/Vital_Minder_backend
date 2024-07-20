<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'created_by',
        'paramedic_staff_id',
        'report_title',
        'report_created_date',
        'physical_examination_note',
        'diagnostic_tests_results',
        'treatment_plan_instruction',
        'doctor_note',
        'patient_name',
        'report_status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paramedicStaff()
    {
        return $this->belongsTo(ParamedicStaff::class);
    }
}
