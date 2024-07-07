<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_name',
        'doctor_phone_number',
        'doctor_email',
        'doctor_password',
        'doctor_gender',
        'doctor_date_of_birth',
        'specialization',
        'clinic_address',
        'qualificatioins',
        'years_of_experience',
        'schedule',
        'account_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
