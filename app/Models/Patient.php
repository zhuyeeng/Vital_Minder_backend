<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'phone_number',
        'identity_card_number',
        'gender',
        'date_of_birth',
        'email',
        'password',
        'user_id',
        'profile_picture',
        'blood_pressure',
        'blood_sugar',
        'height',
        'weight',
        'medical_history',
        'medications',
        'emergency_contact'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(PatientReport::class);
    }
}
