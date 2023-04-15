<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Patient;
use Egulias\EmailValidator\MessageIDParser;
use http\Env\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Psy\Util\Json;

class MedicalRecordController extends Controller
{
    //
    public function index(Patient $patient)
    {

        return \response()->json($patient->medicalRecords()->orderBy('patient_entry_date', 'desc')->paginate());
    }
    public function patientMedicalRecord(Patient $patient, MedicalRecord $medicalRecord): JsonResponse
    {

        $this->authorize('belongings',  [$medicalRecord, $patient]);
        //        echo $patient->id . ' ' .  $record->patient_id;
        $medicalRecord['can_update'] = Auth::user()->can('update', $medicalRecord);
        $medicalRecord['can_delete'] = Auth::user()->can('delete', $medicalRecord);
        return response()->json(['data' => $medicalRecord]);
        //        return response()->json(['data' => $medicalRecord->load([
        //            'monitoringSheets' => function ($query) {
        //                $query->orderBy('filling_date', 'asc');
        //                $query->with('treatments.medicine');
        //            },
        //
        //            'observations.images',
        //            'complementaryExaminations',
        //            'medicineRequests.medicines'
        //        ])]) ;


    }

    public function records(): JsonResponse
    {
        $query = MedicalRecord::query();

        if (\request()->has('doctorId')) {
            $query->where('user_id', \request()->get('doctorId'));
        }
        if (\request()->has('nurseId')) { // add condition for nurse ID
            $query->whereHas('monitoringSheets', function ($q) {
                $q->where('filled_by_id', \request()->get('nurseId'));
            });
        }
        if (\request()->has('patientId')) {
            $query->where('patient_id', \request()->get('patientId'));
        }
        if (\request()->has('isActive')) {
            $query->whereNull('patient_leaving_date');
        }
        if (\request()->has('isInactive')) {
            $query->whereNotNull('patient_leaving_date');
        }
        if (\request()->has('startDate')) {
            $query->whereDate('patient_entry_date', '>=', \request()->get('startDate'));
        }
        if (\request()->has('endDate')) {
            $query->whereDate('patient_entry_date', '<=', \request()->get('endDate'));
        }
        $query->with('patient');
        $query->orderBy('patient_entry_date', 'desc');

        $records = $query->get();

        return response()->json($records);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request, Patient $patient)
    {


        $validatedData = $request->validate([

            'medical_specialty' => 'required|string|max:255',
            'condition_description' => 'required|string|max:255',
            'state_upon_enter' => 'required|string|max:255',
            'standard_treatment' => 'required|string|max:255',
            'state_upon_exit' => 'nullable|string|max:255',
            'bed_number' => 'required|integer',
            'patient_entry_date' => 'required|date',
            'patient_leaving_date' => 'nullable|date|after_or_equal:patient_entry_date',
        ]);
        $validatedData['user_id'] = auth()->user()->id;

        $medicalRecord = $patient->medicalRecords()->create($validatedData);
        $patient->save();
        return response()->json(['message' => 'Medical record created successfully.', 'data' => $medicalRecord]);
    }



    public function update(Patient $patient, MedicalRecord $medicalRecord): JsonResponse
    {

        $this->authorize('belongings',  [$medicalRecord, $patient]);
        $this->authorize('update', $medicalRecord);

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

    public function delete(Patient $patient, MedicalRecord $medicalRecord): JsonResponse
    {

        $this->authorize('delete', $medicalRecord);

        $medicalRecord->delete();

        return response()->json(['message' => 'Medical record deleted successfully.']);
    }

    //    public function patientNotFound(): JsonResponse
    //    {
    //        return response()->json(['error' => 'Patient not found.'] , 404);
    //    }
    //
    //    public function medicalRecordNotFound(): JsonResponse
    //    {
    //        return response()->json(['error' => 'Medical record not found.'] , 404);
    //    }
    //    public function notAuthorized(): JsonResponse
    //    {
    //        return response()->json(['error' => 'Not Authorized.'],401);
    //    }

}
