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

    public function getAllPatient()
    {
        $patients = Patient::all();

        return response()->json([
            'patients' => $patients
        ]);
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
}
