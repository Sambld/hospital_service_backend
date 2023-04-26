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
use Illuminate\Support\Facades\DB;
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

        $this->authorize('belongings', [$medicalRecord, $patient]);
        //        echo $patient->id . ' ' .  $record->patient_id;
        $medicalRecord['can_update'] = Auth::user()->can('update', $medicalRecord);
        $medicalRecord['can_delete'] = Auth::user()->can('delete', $medicalRecord);
        if (\request()->has('withDoctor') && \request()->withDoctor == 'true') {
            $medicalRecord->load('assignedDoctor');
        }
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
        DB::enableQueryLog();

        $query = MedicalRecord::query();

        if (\request()->has('q')) {

            $search = \request()->get('q');
            $query->where(function ($subquery) use ($search) {
                $subquery->where('standard_treatment', 'like', '%' . $search . '%')
                    ->orWhere('condition_description', 'like', '%' . $search . '%')
                    ->orWhere('id', $search );
            });


        }
        if (\request()->has('doctorId')) {
            $query->where('user_id', \request()->get('doctorId'));
        }
        if (\request()->has('nurseId')) { // add condition for nurse ID
            $query->whereHas('monitoringSheets', function ($q) {
                $q->where('filled_by_id', \request()->get('nurseId'));
            });
        }
        // mine records only
        if (\request()->has('mineOnly')) {
            $query->where('user_id', Auth::user()->id);
        }
        if (\request()->has('patientId')) {
            $query->where('patient_id', \request()->get('patientId'));
        }
        if (\request()->has('isActive')) {
            error_log('isActive');
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


        $query->with(['patient', 'assignedDoctor']);
        $query->orderBy('patient_entry_date', 'desc');
        if (\request()->has('withPagination') && \request()->withPagination == 'true') {
            $records = $query->with('patient')->orderBy('patient_entry_date', 'desc')->paginate(12);
            return response()->json($records);
        }


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

        $this->authorize('belongings', [$medicalRecord, $patient]);
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


    //function to search for medical records by standard_treatment or condition_description , to use in public function records(): JsonResponse
    public function search($query, $search)
    {


        $query = $query->where('standard_treatment', 'like', '%' . $search . '%')
            ->orWhere('condition_description', 'like', '%' . $search . '%');
        return $query;
    }


}
