<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paramedic;
use App\Models\Doctor;
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
}
