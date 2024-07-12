<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paramedic;
use App\Models\Doctor;
use App\Models\Patient;
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
}
