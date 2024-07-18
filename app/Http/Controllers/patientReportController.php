<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientReport;

class patientReportController extends Controller
{
    public function storePatientReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'paramedic_staff_id' => 'required|exists:paramedic_staff,id',
            'report' => 'required|file|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('report')->store('reports');

        PatientReport::create([
            'patient_id' => $request->patient_id,
            'paramedic_staff_id' => $request->paramedic_staff_id,
            'report' => $filePath,
        ]);

        return response()->json(['message' => 'Report uploaded successfully'], 200);
    }
}
