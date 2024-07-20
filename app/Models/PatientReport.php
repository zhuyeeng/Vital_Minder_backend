<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'paramedic_staff_id',
        'report',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function paramedicStaff()
    {
        return $this->belongsTo(ParamedicStaff::class);
    }
}
