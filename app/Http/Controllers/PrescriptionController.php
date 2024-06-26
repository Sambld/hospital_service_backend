<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionHelper;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use http\Encoding\Stream;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use function MongoDB\BSON\toJSON;

class PrescriptionController extends Controller
{


    public function index(Patient $patient, MedicalRecord $medicalRecord): JsonResponse
    {

        $this->authorize('index', [Prescription::class, $patient, $medicalRecord]);
        $prescriptions = $medicalRecord->prescriptions;

        return response()->json($prescriptions->load(['medicineRequests.medicine', 'doctor'] ));
    }

    public function prescription(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription): JsonResponse
    {
        $this->authorize('belongings', [Prescription::class, $prescription, $patient, $medicalRecord]);
        //        $this->authorize('view', [Prescription::class, $medicalRecord]);
        return response()->json($prescription->load(['medicineRequests' => function ($query) {
            $query->orderByRaw("FIELD(status , 'Pending' , 'Approved' , 'Rejected')");
        }, 'medicineRequests.medicine' , 'doctor']));
    }

    public function store(Patient $patient, MedicalRecord $medicalRecord): JsonResponse
    {
        $this->authorize('create', [Prescription::class, $patient, $medicalRecord]);

        $prescription = $medicalRecord->prescriptions()->create(['user_id' => auth()->id()]);
        return response()->json(['message' => 'Prescription created successfully', 'data' => $prescription]);
    }


//    public function update(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription): JsonResponse
//    {
//        $this->authorize('updateOrDelete', [Prescription::class, $prescription, $patient, $medicalRecord]);
//        $data = request()->validate([
//            'name' => 'required|string',
//        ]);
//        $prescription->update($data);
//        return response()->json(['message' => 'Prescription updated successfully']);
//    }

    public function delete(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription): JsonResponse
    {
        $this->authorize('updateOrDelete', [Prescription::class, $prescription, $patient, $medicalRecord]);
        $prescription->delete();
        return response()->json(['message' => 'Prescription deleted successfully']);
    }

    public function prescriptionPDF(Patient $patient, MedicalRecord $medicalRecord, Prescription $prescription) : Stream
    {
        // Load the prescription and its related medicine requests and medicines
        $prescription->load('medicineRequests.medicine');

        // Get the hospital details from the application config
        $hospitalName = config('app.hospital.name');
        $hospitalAddress = config('app.hospital.address');
        $hospitalPhone = config('app.hospital.phone');

        // Get the patient details
        $patientName = $patient->first_name . ' ' . $patient->last_name;

        // Calculate the patient's age
        $patientAge = \DateTime::createFromFormat('Y-m-d', $patient->birth_date)->diff(new \DateTime('now'))->y;

        $prescriptionDate = $prescription->created_at->format('M d, Y');


        // get the prescription QR code svg to base64 encode it
        $prescriptionQRCode = base64_encode(
            QrCode::format('svg')->size(100)->generate(
                json_encode([
                    'id' => $prescription->id,
                    'doctor' => $prescription->doctor->first_name . ' ' . $prescription->doctor->last_name,
                    'patient' => $patientName,
                    'date' => $prescriptionDate,
                ])
            )
        );
        // Pass the data to the blade template
        $data = [

            'patient' => $patient,
            'medicalRecord' => $medicalRecord,
            'prescription' => $prescription,
            'prescriptionQRCode' => $prescriptionQRCode,
        ];

        // Generate the HTML using the blade template
        $html = View::make('prescription', $data)->render();

        // Set options for the PDF
        $options = new Options();
        $options->set('defaultFont', 'Sans Serif');

        // Create the PDF object and set options
        $pdf = new Dompdf($options);

        // Load the HTML into the PDF
        $pdf->loadHtml($html);

        // Set paper size and orientation 8.5 inches by 11 inches in size.

        $pdf->setPaper('letter', 'portrait');

        // Render the HTML as PDF
        $pdf->render();

        // Return the generated PDF as file
        return $pdf->stream("prescription_"."$patientName"."_"."$prescriptionDate".".pdf");
    }


    public function pharmacyIndex(Request $request)
    {
        $status = $request->get('status');
        $medicalRecords = MedicalRecord::select('id', 'patient_id', 'user_id', 'condition_description')
            ->whereHas('prescriptions.medicineRequests', function ($query) use ($status) {
                if ($status === 'open') {
                    $query->where('status', '=', 'Pending');
                } elseif ($status === 'closed') {
                    $query->where('status', '!=', 'Pending');
                }
            })
            ->with([
                'prescriptions' => function ($query) use ($status) {
                    $query->orderByDesc('updated_at')
                        ->whereHas('medicineRequests', function ($query) use ($status) {
                            if ($status === 'open') {
                                $query->where('status', '=', 'Pending');
                            } elseif ($status === 'closed') {
                                $query->where('status', '!=', 'Pending');
                            }
                        })
                        ->with([
                            'medicineRequests' => function ($query) use ($status) {
                                $query->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected')")
                                    ->select('id', 'quantity', 'status', 'review', 'prescription_id', 'medicine_id')
                                    ->with([
                                        'medicine' => function ($query) {
                                            $query->select('id', 'name', 'quantity');
                                        }
                                    ]);
                            } , 'doctor'
                        ]);
                },
                'patient' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
                'assignedDoctor' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])
            ->get();

        if (\request()->query('count')) {
            $prescriptionCount = $medicalRecords->flatMap(function ($medicalRecord) {
                return $medicalRecord->prescriptions;
            })->count();

            return response()->json(['count' => $prescriptionCount]);
        }

        return response()->json(CollectionHelper::paginate($medicalRecords, 20));
    }
}
