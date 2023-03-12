<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonitoringSheetController extends Controller
{

    public function store($patient_id , $medical_record_id): JsonResponse
    {
        if (!$this->param_checker($patient_id , $medical_record_id)){
            return  response()->json(['message' => 'patient or medical record not found']) ;
        }

        $validatedData = \request()->validate([
            'filling_date' => 'required|date',
            'urine' => 'nullable|integer',
            'blood_pressure' => 'nullable|string',
            'weight' => 'nullable|integer',
            'temperature' => 'nullable|integer',
            'progress_report' => 'nullable|string',
            'filled_by_id' => 'nullable|exists:users,id',
        ]);
        $validatedData['record_id'] = $medical_record_id;
        $medical_recod = MedicalRecord::find($medical_record_id);

        $sheet = $medical_recod->monitoringSheets()->create($validatedData);
        return  response()->json(['message' => 'Monitoring sheet created successfully.' , 'data' => $sheet]) ;


    }


    public function param_checker($patient_id , $medical_record_id , $sheet_id = null)
    {
        $patient = Patient::find($patient_id)->medicalRecords()->find($medical_record_id);
        return !!$patient;
    }
}
