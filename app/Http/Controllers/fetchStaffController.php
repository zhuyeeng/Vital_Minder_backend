<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paramedic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class fetchStaffController extends Controller
{
    public function getAllMedicalStaff()
    {
        // Get all doctors and paramedics
        $doctors = Doctor::all();
        $paramedics = Paramedic::all();

        // Initialize arrays to hold detailed information
        $doctorDetails = [];
        $paramedicDetails = [];

        // Fetch user details for doctors
        foreach ($doctors as $doctor) {
            $user = User::find($doctor->user_id);
            if ($user) {
                $doctorDetails[] = [
                    'user' => $user,
                    'details' => $doctor
                ];
            }
        }

        // Fetch user details for paramedics
        foreach ($paramedics as $paramedic) {
            $user = User::find($paramedic->user_id);
            if ($user) {
                $paramedicDetails[] = [
                    'user' => $user,
                    'details' => $paramedic
                ];
            }
        }

        return response()->json([
            'doctors' => $doctorDetails,
            'paramedics' => $paramedicDetails
        ]);
    }

    public function getAllPatients()
    {
        $patients = Patient::all(['id', 'username', 'identity_card_number']);
        return response()->json($patients);
    }

    public function getAllMedicalStaffWithDetails()
    {
        $users = User::whereIn('user_role', ['doctor', 'paramedic'])->get();

        $users->each(function ($user) {
            if ($user->user_role === 'doctor') {
                $doctorDetails = Doctor::where('user_id', $user->id)->first();
                $user->details = $doctorDetails;
            } elseif ($user->user_role === 'paramedic') {
                $paramedicDetails = Paramedic::where('user_id', $user->id)->first();
                $user->details = $paramedicDetails;
            }
        });

        return response()->json($users);
    }

    // Method to fetch staff information by user ID
    public function getStaffByUserId($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->user_role === 'doctor') {
            $details = Doctor::where('user_id', $userId)->first();
        } elseif ($user->user_role === 'paramedic') {
            $details = Paramedic::where('user_id', $userId)->first();
        } elseif($user->user_role === "patient") {
            $details = Patient::where('user_id',$userId)->first();
        }else {
            return response()->json(['error' => 'User is not a doctor or paramedic'], 404);
        }

        if (!$details) {
            return response()->json(['error' => 'Details not found'], 404);
        }

        return response()->json(['user' => $user, 'details' => $details]);
    }

    public function getPatientIdByUserId($userId)
    {
        $patient = Patient::where('user_id', $userId)->first();

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        return response()->json(['patient_id' => $patient->id]);
    }

    public function searchPatientsByUsername($username)
    {
        $patients = Patient::where('username', 'LIKE', "%$username%")->get();

        if ($patients->isEmpty()) {
            return response()->json(['error' => 'No patients found'], 404);
        }

        return response()->json($patients);
    }

    public function getAllDoctors()//need modify a bit
    {
        $doctors = Doctor::all(['doctor_name', 'doctor_email', 'doctor_phone_number']);
        return response()->json($doctors);
    }
}
