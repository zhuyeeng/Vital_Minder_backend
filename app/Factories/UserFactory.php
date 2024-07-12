<?php

namespace App\Factories;

use App\Models\Doctor;
use App\Models\Paramedic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    public static function create(array $data)
    {
        $user = User::create([
            'username' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'user_role' => $data['user_role'],
            'identity_card_number' => $data['identity_card_number'],
            'status' => 'active' // Set status as active during registration
        ]);

        if ($user->user_role == 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'doctor_name' => $user->username,
                'doctor_phone_number' => $user->phone_number,
                'doctor_email' => $user->email,
                'doctor_password' => $user->password,
                'doctor_gender' => $user->gender,
                'doctor_date_of_birth' => $user->date_of_birth,
                'specialization' => $data['specialization'],
                'clinic_address' => $data['clinic_address'],
                'qualifications' => $data['qualifications'],
                'years_of_experience' => $data['years_of_experience'],
                'account_status' => 'active',
                'doctor_identity_card_number' => $user->identity_card_number
            ]);
        } elseif ($user->user_role == 'paramedic') {
            Paramedic::create([
                'user_id' => $user->id,
                'paramedic_staff_name' => $user->username,
                'paramedic_staff_phone_number' => $user->phone_number,
                'paramedic_staff_email' => $user->email,
                'paramedic_staff_password' => $user->password,
                'paramedic_staff_gender' => $user->gender,
                'paramedic_staff_date_of_birth' => $user->date_of_birth,
                'qualifications' => $data['qualifications'],
                'field_experience' => $data['field_experience'],
                'assigned_area' => $data['assigned_area'],
                'account_status' => 'active',
                'paramedic_staff_identity_card_number' => $user->identity_card_number
            ]);
        } elseif ($user->user_role == 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'phone_number' => $user->phone_number,
                'email' => $user->email,
                'password' => $user->password,
                'gender' => $user->gender,
                'date_of_birth' => $user->date_of_birth,
                'identity_card_number' => $user->identity_card_number
            ]);
        }

        return $user;
    }
}
