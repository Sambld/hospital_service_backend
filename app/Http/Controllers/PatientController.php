<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    //
    public function patient(Patient $patient): JsonResponse
    {

            return response()->json(['data' => ['patient' => $patient]]);


    }

    public function index(): JsonResponse
    {



        if(\request()->has('q')){
            $patients = $this->search(\request()->q);
            return response()->json([ 'count' => $patients->count(),'data' => $patients ]);

        }
        return response()->json(['data' => Patient::withCount('medicalRecords')->paginate()]);
//        return response()->json(['data' => Patient::withCount('medical_records')->get()]);
    }

    public function store(): JsonResponse
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

    public function search($searchQuery): \Illuminate\Database\Eloquent\Collection|array
    {
        $query = Patient::query();
        $words = preg_split('/\s+/', $searchQuery);
        // Check if the request contains a search query

        foreach ($words as $word) {

            // Use the LIKE operator to search for records with matching first or last name
            $query->where(function ($query) use ($word) {
                $query->where('first_name', 'LIKE', '%' . $word . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $word . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $word . '%');
            });
        }




        // Retrieve the matching records and return them
        return $query->get();
    }
}
