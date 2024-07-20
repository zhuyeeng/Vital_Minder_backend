<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'paramedic_staff_id',
        'monday_start',
        'monday_end',
        'tuesday_start',
        'tuesday_end',
        'wednesday_start',
        'wednesday_end',
        'thursday_start',
        'thursday_end',
        'friday_start',
        'friday_end',
        'saturday_start',
        'saturday_end',
        'sunday_start',
        'sunday_end',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function paramedicStaff()
    {
        return $this->belongsTo(ParamedicStaff::class);
    }
}
