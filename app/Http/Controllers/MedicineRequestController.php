<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Promise\all;

class MedicineRequestController extends Controller
{
    public function request(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription, MedicineRequest $request): JsonResponse
    {
        $this->authorize('belongings', [$request, $patient, $medicalRecord, $prescription]);
        $this->authorize('view', [$request, $medicalRecord]);
        //        $data = $this->add_abilities($request, $patient, $medicalRecord);
        return response()->json(['data' => $request]);
    }

    public function medicineRequestsQuery()
    {
        $query = MedicineRequest::query();



        if (\request()->has('doctorId')) {
            $query->whereHas('medicalRecord', function ($q) {
                $q->where('user_id', \request()->get('doctorId'));
            });
        }

        if (\request()->has('recordId')) {
            $query->where('record_id', \request()->get('recordId'));
        }

        if (\request()->has('patientId')) {
            $query->whereHas('medicalRecord', function ($q) {
                $q->where('patient_id', \request()->get('patientId'));
            });
        }


        if (\request()->has('startDate')) {
            $query->whereDate('created_at', '>=', \request()->get('startDate'));
        }

        if (\request()->has('endDate')) {
            $query->whereDate('created_at', '<=', \request()->get('endDate'));
        }






        $query->with(['medicine']);
        if (\request()->has('count')) {
            $medicineRequests = $query->selectRaw('medicine_id, sum(quantity) as quantity')
                ->where('status', 'Approved')
                ->groupBy('medicine_id')
                ->get();

            return response()->json($medicineRequests);
        }

        $medicineRequests = $query->get();

        return response()->json($medicineRequests);
    }

    public function index(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription): JsonResponse
    {
        // $this->authorize('viewAny', [MedicineRequest::class, $patient, $medicalRecord]);
        //        $this->authorize('create', [MedicineRequest::class, $patient, $medicalRecord]);
        $status = request()->query('status');
        $query = $prescription->medicineRequests();

        if ($status) {
            $query = $query->where('status', $status);
        }

        $requests = $query->orderByDesc('updated_at')->get();

        return response()->json(['data' => $requests->load('medicine')]);
    }

    public function store(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription)
    {
        $this->authorize('create', [MedicineRequest::class, $patient, $medicalRecord, $prescription]);

        $data = \request()->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer',
        ]);
        $request = $prescription->medicineRequests()->create([
            'user_id' => Auth::user()->id,
            'prescription_id' => $prescription->id,
            'medicine_id' => $data['medicine_id'],
            'quantity' => $data['quantity'],
        ]);
        //        $data = $this->add_abilities($request, $patient, $medicalRecord);
        return response()->json(['message' => 'medicine request created successfully.', 'data' => $request]);
    }

    public function update(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription, MedicineRequest $request)
    {


        $this->authorize('belongings', [$request, $patient, $medicalRecord, $prescription]);
        $this->authorize('update', [MedicineRequest::class, $medicalRecord , $prescription]);

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

    public function delete(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription, MedicineRequest $request)
    {

        $this->authorize('belongings', [$request, $patient, $medicalRecord, $prescription]);
        $this->authorize('delete', [$request, $medicalRecord , $prescription]);
        $request->delete();
        return response()->json(['message' => 'medicine request deleted successfully.']);
    }
}
