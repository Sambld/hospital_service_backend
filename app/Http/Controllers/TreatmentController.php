<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Treatment;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{

    public function treatment(Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet, Treatment $treatment): JsonResponse
    {
        $this->authorize('belongings', [$treatment, $patient, $medicalRecord, $monitoringSheet]);
        $data = $this->add_abilities($treatment, $patient, $medicalRecord, $monitoringSheet);
        return response()->json(['data' => $data->load('medicine')]);
    }

    public function index(Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet)
    {
        $this->authorize('viewAny', [Treatment::class, $patient, $medicalRecord, $monitoringSheet]);
        $treatments = $monitoringSheet->treatments;
        $data = [];
        foreach ($treatments as $treatment) {
            $data[] = $this->add_abilities($treatment, $patient, $medicalRecord, $monitoringSheet);
        }
        return response()->json(['data' => $data]);
    }

    public function store(Request $request, Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet)
    {
        error_log('storing');
        $this->authorize('create', [Treatment::class, $patient, $medicalRecord, $monitoringSheet]);
        $data = $request->validate([
            'name' => 'required|string',
            'dose' => 'required|string',
            'type' => 'required|string',
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $monitoringSheet->monitoringSheetLogs()->create([
            'user_id' => Auth::user()->id,
            'action' => 'create',
            'type' => 'treatment',
            'previous_data' => null,
        ]);


        $treatment = $monitoringSheet->treatments()->create($data);
        $data = $this->add_abilities($treatment, $patient, $medicalRecord, $monitoringSheet);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet, Treatment $treatment)
    {
        $this->authorize('updateOrDelete', [$treatment, $patient, $medicalRecord, $monitoringSheet]);
        $data = $request->validate([
            'name' => 'required|string',
            'dose' => 'required|string',
            'type' => 'required|string',
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $monitoringSheet->monitoringSheetLogs()->create([
            'user_id' => Auth::user()->id,
            'action' => 'update',
            'type' => 'treatment',
            'previous_data' => $treatment->toJson(),
        ]);
        $treatment->update($data);
        $data = $this->add_abilities($treatment, $patient, $medicalRecord, $monitoringSheet);
        return response()->json(['data' => $data]);
    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, MonitoringSheet $monitoringSheet, Treatment $treatment)
    {
        $this->authorize('updateOrDelete', [$treatment, $patient, $medicalRecord, $monitoringSheet]);

        $monitoringSheet->monitoringSheetLogs()->create([
            'user_id' => Auth::user()->id,
            'action' => 'delete',
            'type' => 'treatment',
            'previous_data' => $treatment->toJson(),
        ]);
        $treatment->delete();
        return response()->json(['message' => 'Treatment deleted successfully.']);
    }

    private function add_abilities($data, $patient, $medicalRecord, $monitoringSheet)
    {
        $data['can_update'] = Auth::user()->can('updateOrDelete', [$data, $patient, $medicalRecord, $monitoringSheet]);
        $data['can_delete'] = Auth::user()->can('updateOrDelete', [$data, $patient, $medicalRecord, $monitoringSheet]);
        return $data;
    }

}
