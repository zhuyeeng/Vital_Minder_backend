<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientReport;
use Illuminate\Support\Facades\Storage;

class PatientReportController extends Controller
{
    public function storePatientReport(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'paramedic_staff_id' => 'required|exists:paramedic_staff,id',
            'report' => 'required|file|mimes:pdf|max:2048',
            'patient_name' => 'required|string|max:255',
            'report_title' => 'required|string|max:255', // Add this line
        ]);

        $reportPath = null;
        if ($request->hasFile('report')) {
            // Ensure the directory exists
            $directory = 'public/patient_reports';
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }

            // Store the report in the public/patient_reports directory
            $reportPath = $request->file('report')->store($directory);
        }

        PatientReport::create([
            'patient_id' => $request->patient_id,
            'paramedic_staff_id' => $request->paramedic_staff_id,
            'report' => $reportPath,
            'patient_name' => $request->patient_name,
            'report_title' => $request->report_title, // Add this line
        ]);

        return response()->json(['message' => 'Report uploaded successfully'], 200);
    }


    public function getReportsByPatientId($patientId)
    {
        $reports = PatientReport::where('patient_id', $patientId)->get();
        return response()->json($reports);
    }
}
