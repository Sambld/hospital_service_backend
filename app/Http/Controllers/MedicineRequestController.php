<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineRequestController extends Controller
{
    public function request(Patient $patient, MedicalRecord $medicalRecord, MedicineRequest $request): JsonResponse
    {
        $this->authorize('belongings', [$request, $patient, $medicalRecord]);
        $this->authorize('view', [$request, $medicalRecord]);
//        $data = $this->add_abilities($request, $patient, $medicalRecord);
        return response()->json(['data' => $request]);
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

        return response()->json(['data' => $requests]);
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
            if($data['status'] == 'Approved'){
                $currentMedicineQuantity = $request->medicine->quantity;
                if ($currentMedicineQuantity >= $request->quantity){
                    $newMedicineQuantity = $request->medicine->quantity - $request->quantity;
                    $request->medicine()->update(['quantity' => $newMedicineQuantity]);
                }else{
                    return response()->json(['message' => 'insufficient  resource.'] , 401);
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
        return response()->json(['message'=>'medicine request updated successfully.','data' => $data]);
    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, MedicineRequest $request)
    {

        $this->authorize('delete', [$request, $patient, $medicalRecord]);
        $request->delete();
        return response()->json(['message' => 'medicine request deleted successfully.']);
    }
}
