<?php

// UserController.php
namespace App\Http\api\authControllers;

use App\Models\Doctor;
use App\Models\Paramedic;
use Illuminate\Http\Request;

class UserController extends authController
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

