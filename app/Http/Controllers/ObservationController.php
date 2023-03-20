<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Observation;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObservationController extends Controller
{


    public function observation(Patient $patient, MedicalRecord $medicalRecord, Observation $observation): JsonResponse
    {
        $this->authorize('belongings', [$observation, $patient, $medicalRecord,]);
        $data = $this->add_abilities($observation->load('images'), $patient, $medicalRecord);
        return response()->json(['data' => $data]);
    }

    public function index(Patient $patient, MedicalRecord $medicalRecord)
    {
//        dd($patient, $medicalRecord);
        $this->authorize('index', [Observation::class, $patient, $medicalRecord]);
        $observations = $medicalRecord->observations->load('images');
//        dd($observations);
        $data = [];
        foreach ($observations as $observation) {
            $data[] = $this->add_abilities($observation, $patient, $medicalRecord);
        }
        return response()->json(['data' => $data]);
    }

    public function store(Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('create', [Observation::class, $patient, $medicalRecord]);
        $data = \request()->validate([
            'name' => 'required|string',
        ]);

        $observation = $medicalRecord->observations()->create($data);
        $data = $this->add_abilities($observation, $patient, $medicalRecord);
        return response()->json(['data' => $data]);
    }

    public function update(Patient $patient, MedicalRecord $medicalRecord, Observation $observation)
    {
        $this->authorize('updateOrDelete', [$observation, $patient, $medicalRecord]);
        $data = \request()->validate([
            'name' => 'required|string',
        ]);
        $observation->update($data);
        $data = $this->add_abilities($observation, $patient, $medicalRecord);
        return response()->json(['data' => $data]);
    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, Observation $observation)
    {
        $this->authorize('updateOrDelete', [$observation,$patient, $medicalRecord]);
        $observation->delete();
        return response()->json(['message' => 'observation deleted successfully.']);
    }

    private function add_abilities($data, $patient, $medicalRecord) {
        $data['can_update'] = Auth::user()->can('updateOrDelete' ,[$data, $patient, $medicalRecord]);
        $data['can_delete'] = Auth::user()->can('updateOrDelete' ,[$data, $patient, $medicalRecord]);
        return $data;
    }
}
