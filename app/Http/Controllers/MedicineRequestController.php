<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Promise\all;

class MedicineRequestController extends Controller
{
    public function request(Patient $patient, MedicalRecord $medicalRecord, MedicineRequest $request): JsonResponse
    {
        $this->authorize('belongings', [$request, $patient, $medicalRecord]);
        $this->authorize('view', [$request, $medicalRecord]);
//        $data = $this->add_abilities($request, $patient, $medicalRecord);
        return response()->json(['data' => $request]);
    }

    public function pharmacy_index()
    {
        $status = \request()->query('status');

        $records = MedicalRecord::with(['medicineRequests' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        $medicineRequestsPerRecord = $records->map(function ($record) use ($status) {
            $medicineRequests = $record->medicineRequests;
            $unrespondedRequests = $medicineRequests->where('status', 'Pending');
            $all_requests_responded_to = $unrespondedRequests->isEmpty();

            if (($status == 'open' && !$all_requests_responded_to) || ($status == 'closed' && $all_requests_responded_to) || !$status) {
                $medicineRequests = $medicineRequests->sortByDesc('created_at');
                return [
                    'medical_record_id' => $record->id,
                    'doctor' => $record->assignedDoctor->fullname(),
                    'patient' => $record->patient->fullname(),
                    'patient_id' => $record->patient->id,
                    'all_requests_responded_to' => $all_requests_responded_to,
                    'medicine_requests' => $medicineRequests->load('medicine'),
                ];
            }
        })->filter()->values();

        $medicineRequestsPerRecord = CollectionHelper::paginate($medicineRequestsPerRecord, 15);
//        return response()->json(['data' => $medicineRequestsPerRecord]);
        return response()->json(
            $medicineRequestsPerRecord
        );
    }



    public function index(Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('viewAny', [MedicineRequest::class, $patient, $medicalRecord]);
//        $this->authorize('create', [MedicineRequest::class, $patient, $medicalRecord]);
        $status = request()->query('status');
        $query = $medicalRecord->medicineRequests();

        if ($status) {
            $query = $query->where('status', $status);
        }

        $requests = $query->orderByDesc('updated_at')->get();

        return response()->json(['data' => $requests->load('medicine')]);
    }

    public function store(Patient $patient, MedicalRecord $medicalRecord)
    {
        $this->authorize('create', [MedicineRequest::class, $patient, $medicalRecord]);
        $data = \request()->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer',
        ]);
        $medicine = Medicine::find($data['medicine_id']);
        $request = $medicalRecord->medicineRequests()->create([
            'user_id' => Auth::user()->id,
            'medicine_id' => $medicine->id,
            'quantity' => $data['quantity'],
        ]);
//        $data = $this->add_abilities($request, $patient, $medicalRecord);
        return response()->json(['message' => 'medicine request created successfully.', 'data' => $request]);
    }

    public function update(Patient $patient, MedicalRecord $medicalRecord, MedicineRequest $request)
    {


        $this->authorize('belongings', [$request, $patient, $medicalRecord]);
        $this->authorize('update', [MedicineRequest::class, $medicalRecord]);

        if ($request->status == 'Approved') {

            return response()->json(['message' => 'medicine request closed!'], 401);


        } else if (Auth::user()->isPharm()) {

            $data = \request()->validate([
                'status' => 'required|string|in:Approved,Rejected',
                'review' => 'nullable|string',
            ]);
            if ($data['status'] == 'Approved') {
                $currentMedicineQuantity = $request->medicine->quantity;
                if ($currentMedicineQuantity >= $request->quantity) {
                    $newMedicineQuantity = $request->medicine->quantity - $request->quantity;
                    $request->medicine()->update(['quantity' => $newMedicineQuantity]);
                } else {
                    return response()->json(['message' => 'insufficient  resource.'], 401);
                }

            }
            $request->update($data);
            return response()->json(['message' => 'medicine request updated successfully.']);
        } else {
            $data = \request()->validate([
                'medicine_id' => 'required|exists:medicines,id',
                'quantity' => 'required|integer',
            ]);
            $medicine = Medicine::find($data['medicine_id']);
            $request->update([
                'medicine_id' => $medicine->id,
                'quantity' => $data['quantity'],

            ]);
        }

//        $data = $this->add_abilities($request, $patient, $medicalRecord);
        return response()->json(['message' => 'medicine request updated successfully.', 'data' => $data]);
    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, MedicineRequest $request)
    {

        $this->authorize('delete', [$request, $patient, $medicalRecord]);
        $request->delete();
        return response()->json(['message' => 'medicine request deleted successfully.']);
    }
}
