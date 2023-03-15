<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplementaryExaminationController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MonitoringSheetController;
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
    Route::get('/user' , [UserController::class, 'self' ]);
    Route::put('/users/{id}' , [UserController::class, 'update' ]);
    Route::delete('/users/{id}' , [UserController::class, 'delete' ]);
    Route::post('/logout', [AuthController::class, 'logout']);



    Route::get('/patients/{patient}' , [\App\Http\Controllers\PatientController::class,'patient']);
    Route::get('/patients' , [\App\Http\Controllers\PatientController::class,'index']);
    Route::post('/patients' , [\App\Http\Controllers\PatientController::class,'store']);
    Route::put('/patients/{patient}' , [\App\Http\Controllers\PatientController::class,'update']);
    Route::delete('/patients/{patient}' , [\App\Http\Controllers\PatientController::class,'delete']);


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


    Route::get('/patients/{patient}/medical-records/{medical_record}/observations/{observation}' , [\App\Http\Controllers\ObservationController::class,'observation']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/observations' , [\App\Http\Controllers\ObservationController::class,'index']);
    Route::post('/patients/{patient}/medical-records/{medical_record}/observations' , [\App\Http\Controllers\ObservationController::class,'store']);
    Route::put('/patients/{patient}/medical-records/{medical_record}/observations/{observation}' , [\App\Http\Controllers\ObservationController::class,'update']);
    Route::delete('/patients/{patient}/medical-records/{medical_record}/observations/{observation}' , [\App\Http\Controllers\ObservationController::class,'delete']);

});
