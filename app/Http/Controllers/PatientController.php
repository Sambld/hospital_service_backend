<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    //
    public function patient(Patient $patient): JsonResponse
    {
            if (\request()->has('withMedicalRecords')){
                return response()->json(['data' => ['patient' => $patient->load('medicalRecords.assignedDoctor')]]);

            }
            return response()->json(['data' => ['patient' => $patient]]);


    }

    public function index(): JsonResponse
    {
        if(request()->has('count')){
            return response()->json(['count' => Patient::count()]);
        }

        if (\request()->has('onlyMyPatientsCount')){
            return response()->json(['count' => Auth::user()->medicalRecords()->count()]);
        }
        $inHospitalOnly = request()->has('inHospitalOnly');

        if(request()->has('q')){
            $patients = $this->search(request()->q, $inHospitalOnly);
            if ($patients->isEmpty()){
                return response()->json([ 'message' => 'not found!' ],404);
            } else {
                return response()->json([ 'count' => $patients->count(), 'data' => $patients->toQuery()->orderByDesc('created_at')->paginate() ]);
            }
        }


        $patientsQuery = Patient::withCount('medicalRecords');
        if ($inHospitalOnly) {
            $patientsQuery->whereHas('medicalRecords', function ($query) {
                $query->whereNull('patient_leaving_date');
            });
        }
        $patientsQuery->orderByDesc('created_at');

        return response()->json(['data' => $patientsQuery->paginate()]);
    }


    public function store(): JsonResponse
    {
//        error_log(\request());
        $data = request()->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'place_of_birth' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
            'address' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'family_situation' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',
        ]);


        $patient = Patient::create($data);
        return response()->json(['message' => 'Patient created successfully.', 'patient' => $patient]);
    }

    public function update(Patient $patient): JsonResponse
    {


        $data = request()->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'place_of_birth' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
            'address' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'family_situation' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:255',
        ]);

        $patient->update($data);
        return response()->json(['message' => 'Patient updated successfully.', 'patient' => $patient]);


    }

    public function delete(Patient $patient): JsonResponse
    {


        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully.']);
    }

    public function search($searchQuery, $inHospitalOnly = false): \Illuminate\Database\Eloquent\Collection|array
    {
        $query = Patient::query();
        $words = preg_split('/\s+/', $searchQuery);

        foreach ($words as $word) {
            $query->where(function ($query) use ($word) {
                $query->where('first_name', 'LIKE', '%' . $word . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $word . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $word . '%');
            });
        }

        if ($inHospitalOnly) {
            $query->whereHas('medicalRecords', function ($query) {
                $query->whereNull('patient_leaving_date');
            });
        }

        return $query->get();
    }
}
