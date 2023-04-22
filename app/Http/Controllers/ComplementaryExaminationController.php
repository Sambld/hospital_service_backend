<?php

namespace App\Http\Controllers;

use App\Models\ComplementaryExamination;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;
use Psy\Util\Json;

class ComplementaryExaminationController extends Controller
{
    //

    public function examination(Patient $patient , MedicalRecord $medicalRecord ,ComplementaryExamination $complementaryExamination) : JsonResponse
    {
        $this->authorize('belongings', [$complementaryExamination, $patient, $medicalRecord,]);
        $data = $this->add_abilities(collect($complementaryExamination), $patient, $medicalRecord);

        return response()->json(['data' => $data]);
    }

    public function index(Patient $patient , MedicalRecord $medicalRecord ) : JsonResponse
    {
        $this->authorize('create', [ComplementaryExamination::class ,$patient, $medicalRecord]);
        // order by createdAt desc
//        $medicalRecord->complementaryExaminations = $medicalRecord->complementaryExaminations->sortByDesc('created_at');
        $data = $this->add_abilities($medicalRecord->complementaryExaminations, $patient, $medicalRecord);


        return response()->json(['data' => $data]);


    }

    public function store( Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('create', [ComplementaryExamination::class ,$patient, $medicalRecord]);

        $validatedData = \request()->validate([
            'type' => 'required',
            'result' => 'required',
        ]);

        $complementaryExamination = new ComplementaryExamination($validatedData);
        $complementaryExamination->medical_record_id = $medicalRecord->id;
        $complementaryExamination->save();

        $this->add_abilities(collect([$complementaryExamination]), $patient, $medicalRecord);

        return response()->json(['data' => $complementaryExamination]);
    }

    public function update( Patient $patient, MedicalRecord $medicalRecord, ComplementaryExamination $examination)
    {
        $this->authorize('updateOrDelete', [$examination, $patient, $medicalRecord]);

        $validatedData = \request()->validate([
            'type' => 'required',
            'result' => 'required',
        ]);

        $examination->update($validatedData);

        $this->add_abilities(collect([$examination]), $patient, $medicalRecord);

        return response()->json(['data' => $examination]);
    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, ComplementaryExamination $examination)
    {
        $this->authorize('updateOrDelete', [$examination, $patient, $medicalRecord]);

        $examination->delete();

        return response()->json(['message' => 'Complementary examination deleted successfully']);
    }

    private function add_abilities($data, $patient, $medicalRecord) {
        return $data->map(function ($c) use ($patient,$medicalRecord){
            $c['can_update'] = Auth::user()->can('updateOrDelete' ,[$c, $patient, $medicalRecord,] );
            $c['can_delete'] = Auth::user()->can('updateOrDelete' ,[$c, $patient, $medicalRecord,] );
            return $c;
        });
    }
}
