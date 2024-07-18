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
        $doctors = Doctor::all();
        $paramedics = Paramedic::all();

        return response()->json([
            'doctors' => $doctors,
            'paramedics' => $paramedics
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

    public function getParamedicIdByUserId($userId)
    {
        $paramedic = Paramedic::where('user_id', $userId)->first();

        if (!$paramedic) {
            return response()->json(['error' => 'Paramedic not found'], 404);
        }

        return response()->json(['paramedic_id' => $paramedic->id]);
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
}
