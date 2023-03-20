<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplementaryExaminationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MandatoryDeclarationController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineRequestController;
use App\Http\Controllers\MonitoringSheetController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/user', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users/{id}' , [UserController::class, 'index' ]);
    Route::get('/user' , [UserController::class, 'self' ]);
    Route::put('/users/{id}' , [UserController::class, 'update' ]);
    Route::delete('/users/{id}' , [UserController::class, 'delete' ]);
    Route::post('/logout', [AuthController::class, 'logout']);



    Route::get('/patients/{patient}' , [PatientController::class,'patient']);
    Route::get('/patients' , [PatientController::class,'index']);
    Route::post('/patients' , [PatientController::class,'store']);
    Route::put('/patients/{patient}' , [PatientController::class,'update']);
    Route::delete('/patients/{patient}' , [PatientController::class,'delete']);


    Route::get('/patients/{patient}/medical-records/{medical_record}' , [MedicalRecordController::class,'patientMedicalRecord']);
    Route::get('/patients/{patient}/medical-records' , [MedicalRecordController::class,'index']);
//    Route::get('/patients' , [\App\Http\Controllers\MedicalRecordController::class,'index']);
    Route::post('/patients/{patient}/medical-records' , [MedicalRecordController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}' , [MedicalRecordController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}' , [MedicalRecordController::class,'delete']);

    Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}' , [MonitoringSheetController::class,'monitoringSheet']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets' , [MonitoringSheetController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets' , [MonitoringSheetController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}' , [MonitoringSheetController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}' , [MonitoringSheetController::class,'delete']);





    Route::get('/patients/{patient}/medical-records/{medical_record}/examinations/{examination}' , [ComplementaryExaminationController::class,'examination']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/examinations' , [ComplementaryExaminationController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/examinations' , [ComplementaryExaminationController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/examinations/{examination}' , [ComplementaryExaminationController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/examinations/{examination}' , [ComplementaryExaminationController::class,'delete']);

    Route::get('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration' , [MandatoryDeclarationController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration' , [MandatoryDeclarationController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration' , [MandatoryDeclarationController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration' , [MandatoryDeclarationController::class,'delete']);



    Route::get('/patients/{patient}/medical-records/{medical_record}/observations/{observation}' , [ObservationController::class,'observation']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/observations' , [ObservationController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/observations' , [ObservationController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/observations/{observation}' , [ObservationController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/observations/{observation}' , [ObservationController::class,'delete']);


    Route::get('/patients/{patient}/medical-records/{medical_record}/observations/{observation}/images' , [ImageController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/observations/{observation}/images' , [ImageController::class,'store']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/observations/{observation}/images/{image}' , [ImageController::class,'delete']);



    Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}' , [MonitoringSheetController::class,'monitoringSheet']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets' , [MonitoringSheetController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets' , [MonitoringSheetController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}' , [MonitoringSheetController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}' , [MonitoringSheetController::class,'delete']);


    Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments/{treatment}' , [TreatmentController::class,'treatment']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments' , [TreatmentController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments' , [TreatmentController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments/{treatment}' , [TreatmentController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments/{treatment}' , [TreatmentController::class,'delete']);


    Route::get('/medicines/{medicine}' , [\App\Http\Controllers\MedicineController::class,'medicine']);
    Route::get('/medicines' , [\App\Http\Controllers\MedicineController::class,'index']);
    Route::post('/medicines/' , [\App\Http\Controllers\MedicineController::class,'store']);
    Route::put('/medicines/{medicine}' , [\App\Http\Controllers\MedicineController::class,'update']);
    Route::delete('/medicines/{medicine}' , [\App\Http\Controllers\MedicineController::class,'delete']);

    Route::get('/patients/{patient}/medical-records/{medical_record}/medicine-requests/{request}' , [MedicineRequestController::class,'request']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/medicine-requests' , [MedicineRequestController::class,'index']);
    Route::get('/medicine-requests' , [MedicineRequestController::class,'pharmacy_index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/medicine-requests' , [MedicineRequestController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/medicine-requests/{request}' , [MedicineRequestController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/medicine-requests/{request}' , [MedicineRequestController::class,'delete']);

});
