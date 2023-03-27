<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Patient;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    /**
     * @throws AuthorizationException
     */


    public function monitoringSheet(Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet): JsonResponse
    {
        $this->authorize('belongings', [$monitoringSheet, $patient, $medicalRecord,]);
        $monitoringSheet = $this->add_abilities($monitoringSheet, $patient, $medicalRecord);

        return response()->json(['data' => $monitoringSheet->load('treatments')]);
    }

    public function index(Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('create' , [MonitoringSheet::class,$patient, $medicalRecord]);
        $sheets = $medicalRecord->monitoringSheets()->orderBy('filling_date' , 'asc')->get();
        $response = $sheets->map(function ($sheet) use ($patient, $medicalRecord) {
            return $this->add_abilities($sheet, $patient, $medicalRecord);
        });
        return response()->json(['data' => $response->load('treatments')]);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Patient $patient, MedicalRecord $medicalRecord): JsonResponse
    {
//        dd($patient, $medicalRecord);
//        $this->authorize('create', [MonitoringSheet::class, $patient, $medicalRecord]);

        $this->authorize('create' , [MonitoringSheet::class,$patient, $medicalRecord]);
        $validatedData = \request()->validate([
            'filling_date' => 'required|date',
            'urine' => 'nullable|integer',
            'blood_pressure' => 'nullable|string',
            'weight' => 'nullable|integer',
            'temperature' => 'nullable|integer',
            'progress_report' => 'nullable|string',
            'filled_by_id' => 'nullable|exists:users,id',
        ]);

        $validatedData['record_id'] = $medicalRecord->id;


        $sheet = $medicalRecord->monitoringSheets()->create($validatedData);
        return response()->json(['message' => 'Monitoring sheet created successfully.', 'data' => $sheet]);


    }

    /**
     * @throws AuthorizationException
     */
    public function update(Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet): JsonResponse
    {
        $this->authorize('belongings', [$monitoringSheet, $patient, $medicalRecord,]);

        $this->authorize('update', [$monitoringSheet, $patient, $medicalRecord,]);
//        $this->authorize('update',[$monitoringSheet , $patient , $medicalRecord]);

        //        if (!$this->param_checker($patient_id , $medical_record_id , $monitoring_sheet_id , true)){
//            return  $this->notFound();
//        }

//
        $validatedData = \request()->validate([
            'urine' => 'nullable|integer',
            'blood_pressure' => 'nullable|string',
            'weight' => 'nullable|integer',
            'temperature' => 'nullable|integer',
            'progress_report' => 'nullable|string',
        ]);



        $validatedData['filled_by_id'] = auth()->user()->id;
        $monitoringSheet->update($validatedData);
        $monitoringSheet->refresh();
        return response()->json(['message' => 'Monitoring sheet updated successfully.' , 'data' => $monitoringSheet]);


    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet):JsonResponse
    {
        $this->authorize('belongings', [$monitoringSheet, $patient, $medicalRecord,]);
        $this->authorize('delete' , [$monitoringSheet, $medicalRecord]);

        $monitoringSheet->delete();
        return response()->json(['message' => 'monitoring sheet deleted successfully!']);

    }
    public function add_abilities($model, $patient, $medicalRecord) {
        $model['can_update'] = Auth::user()->can('update', [$model, $patient, $medicalRecord]);
        $model['can_delete'] = Auth::user()->can('delete', [$model, $medicalRecord]);
        return $model;
    }
}
