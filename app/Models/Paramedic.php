<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paramedic extends Model
{
    use HasFactory;

    protected $table = 'paramedic_staff';

    protected $fillable = [
        'user_id',
        'paramedic_staff_name',
        'paramedic_staff_phone_number',
        'paramedic_staff_email',
        'paramedic_staff_password',
        'paramedic_staff_gender',
        'paramedic_staff_date_of_birth',
        'qualifications',
        'field_experience',
        'assigned_area',
        'schedule',
        'account_status',
        'profile_picture',
        'paramedic_staff_identity_card_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}