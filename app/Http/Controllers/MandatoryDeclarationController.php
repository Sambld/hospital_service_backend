<?php

namespace App\Http\Controllers;

use App\Models\MandatoryDeclaration;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MandatoryDeclarationController extends Controller
{
    public function index(Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('belongings', [MandatoryDeclaration::class,$patient, $medicalRecord]);

        $mandatoryDeclaration = $medicalRecord->mandatoryDeclaration;

        $data = $this->add_abilities($mandatoryDeclaration, $patient, $medicalRecord);

        return response()->json(['data' => $data]);
    }

    public function store(Request $request, Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('belongings', [MandatoryDeclaration::class,$patient, $medicalRecord]);

        $this->authorize('create', [MandatoryDeclaration::class,$medicalRecord]);

        if ($medicalRecord->mandatoryDeclaration()->exists()) {
            return response()->json(['message' => 'A mandatory declaration already exists for this medical record.'], 409);
        }

        $data = $request->validate([
            'diagnosis' => 'required|string',
            'detail' => 'required|string',
        ]);

        $mandatoryDeclaration = $medicalRecord->mandatoryDeclaration()->create($data);

        $data = $this->add_abilities($mandatoryDeclaration, $patient, $medicalRecord);

        return response()->json(['data' => $data], 201);
    }

    public function update(Request $request, Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('belongings', [MandatoryDeclaration::class,$patient, $medicalRecord]);

        $this->authorize('update', [MandatoryDeclaration::class,$medicalRecord]);

        $mandatoryDeclaration = $medicalRecord->mandatoryDeclaration;

        if (!$mandatoryDeclaration) {
            return response()->json(['message' => 'No mandatory declaration found for this medical record.'], 404);
        }

        $data = $request->validate([
            'diagnosis' => 'required|string',
            'detail' => 'required|string',
        ]);

        $mandatoryDeclaration->update($data);

        $data = $this->add_abilities($mandatoryDeclaration, $patient, $medicalRecord);

        return response()->json(['data' => $data]);
    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('belongings', [MandatoryDeclaration::class,$patient, $medicalRecord]);

        $this->authorize('delete', [MandatoryDeclaration::class,$medicalRecord]);

        $mandatoryDeclaration = $medicalRecord->mandatoryDeclaration;

        if (!$mandatoryDeclaration) {
            return response()->json(['message' => 'No mandatory declaration found for this medical record.'], 404);
        }

        $mandatoryDeclaration->delete();

        return response()->json(['message' => 'Mandatory declaration deleted successfully.']);
    }

    private function add_abilities($data, $patient, $medicalRecord)
    {
        $data['can_update'] = Auth::user()->can('update', [$medicalRecord, $patient]);
        $data['can_delete'] = Auth::user()->can('delete', [$medicalRecord, $patient]);
        return $data;
    }
}
