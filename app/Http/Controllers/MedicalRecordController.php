<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Patient;
use http\Env\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class MedicalRecordController extends Controller
{
    //
    public function index($patient_id)
    {
        $patient = Patient::find($patient_id);
        if (!$patient)  return $this->patientNotFound();


        return \response()->json( $patient->medicalRecords()->orderBy('patient_entry_date', 'desc')->paginate());

    }
    public function patientMedicalRecord($patient_id, $medical_record_id): JsonResponse
    {

        $patient = Patient::find($patient_id);
        $record = MedicalRecord::find($medical_record_id);
        if (!$patient) {
            return $this->patientNotFound();
        }
        if (!$record) {
            return $this->medicalRecordNotFound();
        }
//        echo $patient->id . ' ' .  $record->patient_id;
        return ($patient->id == $record->patient_id) ? response()->json(['data' => $record->load([
            'monitoringSheets' => function ($query) {
                $query->orderBy('filling_date', 'asc');
                $query->with('treatments.medicine');
            },

            'observations.images',
            'complementaryExaminations',
            'medicineRequests.medicines'
        ])]) : $this->notAuthorized() ;


    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request, $patient_id)
    {

        $patient = Patient::find($patient_id);
        if (!$patient) {
            return $this->patientNotFound();
        }

        $validatedData = $request->validate([

            'medical_specialty' => 'required|string|max:255',
            'condition_description' => 'required|string|max:255',
            'state_upon_enter' => 'required|string|max:255',
            'standard_treatment' => 'required|string|max:255',
            'state_upon_exit' => 'required|string|max:255',
            'bed_number' => 'required|integer',
            'patient_entry_date' => 'required|date',
            'patient_leaving_date' => 'nullable|date|after_or_equal:patient_entry_date',
        ]);
        $validatedData['user_id'] = auth()->user()->id;

        $medicalRecord = $patient->medicalRecords()->create($validatedData);
        $patient->save();
        return response()->json(['message' => 'Medical record created successfully.', 'data' => $medicalRecord]);


    }



    public function update( $patient_id, $medical_record_id): JsonResponse
    {
        $patient = Patient::find($patient_id);
        $medicalRecord = MedicalRecord::find($medical_record_id);
        if (!$patient ) {
            return $this->patientNotFound();
        }
        elseif(!$medicalRecord){
            return $this->medicalRecordNotFound();
        }elseif ($medicalRecord->patient_id != $patient_id){
            return $this->notAuthorized();
        }

        $validatedData = \request()->validate([
            'medical_specialty' => 'required|string|max:255',
            'condition_description' => 'required|string|max:255',
            'state_upon_enter' => 'required|string|max:255',
            'standard_treatment' => 'required|string|max:255',
            'state_upon_exit' => 'required|string|max:255',
            'bed_number' => 'required|integer',
            'patient_entry_date' => 'required|date',
            'patient_leaving_date' => 'nullable|date|after_or_equal:patient_entry_date',
        ]);

        $medicalRecord->update($validatedData);

        return response()->json(['message' => 'Medical record updated successfully.', 'data' => $medicalRecord]);


    }

    public function delete($patient_id,$medical_record_id) : JsonResponse
    {
        $patient = Patient::find($patient_id);
        $medicalRecord = MedicalRecord::find($medical_record_id);
        if (!$patient ) {
            return $this->patientNotFound();
        }
        elseif(!$medicalRecord){
            return $this->medicalRecordNotFound();
        }elseif ($medicalRecord->patient_id != $patient_id){
            return $this->notAuthorized();
        }

        $medicalRecord->delete();

        return response()->json(['message' => 'Medical record deleted successfully.']);

    }

    public function patientNotFound(): JsonResponse
    {
        return response()->json(['error' => 'Patient not found.'] , 404);
    }

    public function medicalRecordNotFound(): JsonResponse
    {
        return response()->json(['error' => 'Medical record not found.'] , 404);
    }
    public function notAuthorized(): JsonResponse
    {
        return response()->json(['error' => 'Not Authorized.'],401);
    }

}
