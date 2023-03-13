<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Patient;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MonitoringSheetController extends Controller
{


//    public function monitoringSheet($patient_id , $medical_record_id , $monitoring_sheet_id)
//    {
//        if (!$this->param_checker($patient_id , $medical_record_id , $monitoring_sheet_id)){
//            return $this->notFound();
//        }
//        $sheet = MonitoringSheet::find($monitoring_sheet_id)->load('treatments.medicine');
//        return response()->json(['data' => $sheet]);
//    }
    public function monitoringSheet(Patient $patient , MedicalRecord $medicalRecord , MonitoringSheet $monitoringSheet): JsonResponse
    {
        $this->authorize('monitoringSheet' ,[$patient , $medicalRecord , $monitoringSheet]);
        return response()->json(['data' => $monitoringSheet]);
    }
    public function store($patient_id , $medical_record_id): JsonResponse
    {
        if (!$this->param_checker($patient_id , $medical_record_id)){
            return  response()->json(['message' => 'patient or medical record not found' , 404]) ;
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
        $medical_record = MedicalRecord::find($medical_record_id);

        $sheet = $medical_record->monitoringSheets()->create($validatedData);
        return  response()->json(['message' => 'Monitoring sheet created successfully.' , 'data' => $sheet]) ;


    }

    /**
     * @throws AuthorizationException
     */
    public function update(Patient $patient , MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet): JsonResponse
    {

        echo'autorized';
        if (Gate::denies('update', [$monitoringSheet,$patient, $medicalRecord, ])) {
            abort(403, 'Unauthorized action.');
        }
//        $this->authorize('update',[$monitoringSheet , $patient , $medicalRecord]);

       //        if (!$this->param_checker($patient_id , $medical_record_id , $monitoring_sheet_id , true)){
//            return  $this->notFound();
//        }

//
//        $validatedData = \request()->validate([
//            'filling_date' => 'required|date',
//            'urine' => 'nullable|integer',
//            'blood_pressure' => 'nullable|string',
//            'weight' => 'nullable|integer',
//            'temperature' => 'nullable|integer',
//            'progress_report' => 'nullable|string',
//            'filled_by_id' => 'nullable|exists:users,id',
//        ]);
//
//
//
//
//        $sheet = $medicalRecord->monitoringSheets()->create($validatedData);
        return  response()->json(['message' => 'Monitoring sheet created successfully.' ]) ;
//        return  response()->json(['message' => 'Monitoring sheet created successfully.' , 'data' => $sheet]) ;
//

    }


    public function param_checker($patient_id , $medical_record_id , $sheet_id = null , $isAuth = null)
    {

        $check = Patient::find($patient_id)->medicalRecords()->find($medical_record_id);
        global $sheet;
        $response = [];
        if ($sheet_id && $check) {
//            echo $check;
            $sheet = $check->monitoringSheets()->find($sheet_id);
        }
        else if ($isAuth && $sheet && ($sheet->user_id == auth()->user()->id || $sheet->filled_by == auth()->user()->id)) {
            return false;
        }
        return !!$sheet;
    }

    public function notFound()
    {
       return response()->json(['message' => 'Not found!'] , 404) ;
    }
    public function notAuthorized()
    {
        return response()->json(['message' => 'Not Authorized!'] , 401) ;
    }
}
