<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
    Route::put('/users/{id}' , [UserController::class, 'update' ]);
    Route::delete('/users/{id}' , [UserController::class, 'delete' ]);
    Route::post('/logout', [AuthController::class, 'logout']);



    Route::get('/patients/{id}' , [\App\Http\Controllers\PatientController::class,'patient']);
    Route::get('/patients' , [\App\Http\Controllers\PatientController::class,'index']);
    Route::post('/patients' , [\App\Http\Controllers\PatientController::class,'store']);
    Route::put('/patients/{id}' , [\App\Http\Controllers\PatientController::class,'update']);
    Route::delete('/patients/{id}' , [\App\Http\Controllers\PatientController::class,'delete']);


    Route::get('/patients/{patient_id}/medical-records/{medical_record_id}' , [\App\Http\Controllers\MedicalRecordController::class,'patientMedicalRecord']);
    Route::get('/patients/{patient_id}/medical-records' , [\App\Http\Controllers\MedicalRecordController::class,'index']);
//    Route::get('/patients' , [\App\Http\Controllers\MedicalRecordController::class,'index']);
    Route::post('/patients/{patient_id}/medical-records' , [\App\Http\Controllers\MedicalRecordController::class,'store']);
    Route::put('/patients/{patient_id}/medical-records/{medical_record_id}' , [\App\Http\Controllers\MedicalRecordController::class,'update']);
    Route::delete('/patients/{patient_id}/medical-records/{medical_record_id}' , [\App\Http\Controllers\MedicalRecordController::class,'delete']);

    Route::get('/patients/{patient_id}/medical-records/{medical_record_id}/monitoring-sheets/{monitoring_sheet_id}' , [\App\Http\Controllers\MonitoringSheetController::class,'medicalRecord_monitoringSheet']);
    Route::get('/patients/{patient_id}/medical-records/{medical_record_id}/monitoring-sheets' , [\App\Http\Controllers\MonitoringSheetController::class,'index']);
    Route::post('/patients/{patient_id}/medical-records/{medical_record_id}/monitoring-sheets' , [\App\Http\Controllers\MonitoringSheetController::class,'store']);
    Route::put('/patients/{patient_id}/medical-records/{medical_record_id}/monitoring-sheets/{monitoring_sheet_id}' , [\App\Http\Controllers\MonitoringSheetController::class,'update']);
    Route::delete('/patients/{patient_id}/medical-records/{medical_record_id}/monitoring-sheets/{monitoring_sheet_id}' , [\App\Http\Controllers\MonitoringSheetController::class,'delete']);


});
