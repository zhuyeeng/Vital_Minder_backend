<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Paramedic;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Log the entire request data
        Log::info($request->all());

        // Define validation rules based on the user role
        $commonRules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'identity_card_number' => 'required|unique:users,identity_card_number',
            'user_role' => 'required|in:patient,doctor,paramedic'
        ];

        $roleSpecificRules = [];

        if ($request->user_role == 'doctor') {
            $roleSpecificRules = [
                'qualifications' => 'required',
                'specialization' => 'required',
                'clinic_address' => 'required',
                'years_of_experience' => 'required|integer'
            ];
        } elseif ($request->user_role == 'paramedic') {
            $roleSpecificRules = [
                'qualifications' => 'required',
                'assigned_area' => 'required',
                'field_experience' => 'required|integer'
            ];
        }

        $validator = Validator::make($request->all(), array_merge($commonRules, $roleSpecificRules));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 422);
        }

        // Create the user
        $user = User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'user_role' => $request->user_role,
            'identity_card_number' => $request->identity_card_number,
            'status' => 'active' // Set status as active during registration
        ]);

        // Create the role-specific model
        if ($user->user_role == 'patient') {
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
        } else if ($user->user_role == 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'doctor_name' => $user->username,
                'doctor_phone_number' => $user->phone_number,
                'doctor_email' => $user->email,
                'doctor_password' => $user->password,
                'doctor_gender' => $user->gender,
                'doctor_date_of_birth' => $user->date_of_birth,
                'specialization' => $request->specialization,
                'clinic_address' => $request->clinic_address,
                'qualifications' => $request->qualifications, // Ensure this matches
                'years_of_experience' => $request->years_of_experience,
                'account_status' => 'active', // Default to active
                'doctor_identity_card_number' => $user->identity_card_number
            ]);
        } else if ($user->user_role == 'paramedic') {
            Paramedic::create([
                'user_id' => $user->id,
                'paramedic_staff_name' => $user->username,
                'paramedic_staff_phone_number' => $user->phone_number,
                'paramedic_staff_email' => $user->email,
                'paramedic_staff_password' => $user->password,
                'paramedic_staff_gender' => $user->gender,
                'paramedic_staff_date_of_birth' => $user->date_of_birth,
                'qualifications' => $request->qualifications, // Ensure this matches
                'field_experience' => $request->field_experience,
                'assigned_area' => $request->assigned_area,
                'account_status' => 'active', // Default to active
                'paramedic_staff_identity_card_number' => $user->identity_card_number
            ]);
        }

        // Generate token for the new user
        //$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'true',
            'message' => 'User Register Successful',
            //'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->status === 'banned') {
            return response()->json([
                'status' => 'false',
                'message' => 'Your account is banned. Please contact support.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'true',
            'message' => 'Login Successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}