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
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;
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


Route::post('/user', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [UserController::class, 'self']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // group users routes with admin middleware
    Route::middleware('admin')->group(function () {
        Route::get('/users/{id}', [UserController::class, 'index']);
        Route::get('/users', [UserController::class, 'users']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'delete']);
    });

    // doctor routes
    Route::middleware('doctor')->group(function (){
        // patient management
        Route::post('/patients' , [PatientController::class,'store']);
        Route::put('/patients/{patient}' , [PatientController::class,'update']);
        Route::delete('/patients/{patient}' , [PatientController::class,'delete']);
        Route::get('/patients/statistics' , [StatisticsController::class,'doctorPatientsStatistics']);


        // medical record management
        Route::post('/patients/{patient}/medical-records', [MedicalRecordController::class, 'store']);
        Route::put('/patients/{patient}/medical-records/{medical_record}', [MedicalRecordController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}', [MedicalRecordController::class, 'delete']);
        Route::get('/medical-records/statistics', [StatisticsController::class, 'doctorMedicalRecordsStatistics']);

        // monitoring sheet management
        Route::post('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets', [MonitoringSheetController::class, 'store']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}', [MonitoringSheetController::class, 'delete']);

        // observation management
        Route::post('/patients/{patient}/medical-records/{medical_record}/observations', [ObservationController::class, 'store']);
        Route::put('/patients/{patient}/medical-records/{medical_record}/observations/{observation}', [ObservationController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/observations/{observation}', [ObservationController::class, 'delete']);

        // complementary examination management
        Route::post('/patients/{patient}/medical-records/{medical_record}/examinations', [ComplementaryExaminationController::class, 'store']);
        Route::put('/patients/{patient}/medical-records/{medical_record}/examinations/{examination}', [ComplementaryExaminationController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/examinations/{examination}', [ComplementaryExaminationController::class, 'delete']);

        // treatment management
        Route::post('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments', [TreatmentController::class, 'store']);
        Route::put('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments/{treatment}', [TreatmentController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments/{treatment}', [TreatmentController::class, 'delete']);


        // image management
        Route::post('/patients/{patient}/medical-records/{medical_record}/observations/{observation}/images', [ImageController::class, 'store']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/observations/{observation}/images/{image}', [ImageController::class, 'delete']);


        // mandatory declaration management
        Route::post('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration', [MandatoryDeclarationController::class, 'store']);
        Route::put('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration', [MandatoryDeclarationController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration', [MandatoryDeclarationController::class, 'delete']);

        // prescription management
        Route::post('/patients/{patient}/medical-records/{medical_record}/prescriptions', [PrescriptionController::class, 'store']);
        Route::put('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}', [PrescriptionController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}', [PrescriptionController::class, 'delete']);


        // medicine request management
        Route::post('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}/medicine-requests', [MedicineRequestController::class, 'store']);

        // statistics
        Route::get('/monitoring-sheets/latest-updates', [\App\Http\Controllers\StatisticsController::class, 'doctorMonitoringSheetsLatestUpdates']);


    });


    Route::middleware('DoctorOrNurse')->group(function (){

        // monitoring sheet view and edit
        Route::put('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}', [MonitoringSheetController::class, 'update']);



    });

    Route::middleware('DoctorOrNurseOrAdmin')->group(function (){
        // patient view
        Route::get('/patients/{patient}' , [PatientController::class,'patient']);
        Route::get('/patients' , [PatientController::class,'index']);

        // medical record view
        Route::get('/patients/{patient}/medical-records/{medical_record}', [MedicalRecordController::class, 'patientMedicalRecord']);
        Route::get('/patients/{patient}/medical-records', [MedicalRecordController::class, 'index']);
        Route::get('/medical-records', [MedicalRecordController::class, 'records']);

        // monitoring sheet view
        Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}', [MonitoringSheetController::class, 'monitoringSheet']);
        Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets', [MonitoringSheetController::class, 'index']);

        // observation view
        Route::get('/patients/{patient}/medical-records/{medical_record}/observations/{observation}', [ObservationController::class, 'observation']);
        Route::get('/patients/{patient}/medical-records/{medical_record}/observations', [ObservationController::class, 'index']);


        // complementary examination view
        Route::get('/patients/{patient}/medical-records/{medical_record}/examinations/{examination}', [ComplementaryExaminationController::class, 'examination']);
        Route::get('/patients/{patient}/medical-records/{medical_record}/examinations', [ComplementaryExaminationController::class, 'index']);

        // treatment view

        Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments/{treatment}', [TreatmentController::class, 'treatment']);
        Route::get('/patients/{patient}/medical-records/{medical_record}/monitoring-sheets/{monitoring_sheet}/treatments', [TreatmentController::class, 'index']);


        // image view

        Route::get('/patients/{patient}/medical-records/{medical_record}/observations/{observation}/images', [ImageController::class, 'index']);

        // mandatory declaration view
        Route::get('/patients/{patient}/medical-records/{medical_record}/mandatory-declaration', [MandatoryDeclarationController::class, 'index']);


        // prescription view
        Route::get('/patients/{patient}/medical-records/{medical_record}/prescriptions', [PrescriptionController::class, 'index']);
        Route::get('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}', [PrescriptionController::class, 'prescription']);
        Route::get('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'prescriptionPDF']);

    });

    Route::middleware('DoctorOrPharmacist')->group(function (){
        // medicine request management
        Route::put('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}/medicine-requests/{request}', [MedicineRequestController::class, 'update']);
        Route::delete('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}/medicine-requests/{request}', [MedicineRequestController::class, 'delete']);

    });

    Route::middleware('pharmacist')->group(
        function (){
            Route::post('/medicines', [\App\Http\Controllers\MedicineController::class, 'store']);
            Route::put('/medicines/{medicine}', [\App\Http\Controllers\MedicineController::class, 'update']);
            Route::delete('/medicines/{medicine}', [\App\Http\Controllers\MedicineController::class, 'delete']);
            Route::get('/medicines/statistics', [\App\Http\Controllers\StatisticsController::class, 'PharmacistMedicinesStatistics']);

            Route::get('/medicine-requests', [MedicineRequestController::class, 'pharmacy_index']);
            Route::get('/medicine-requests/query', [MedicineRequestController::class, 'medicineRequestsQuery']);

            Route::get('/prescriptions', [PrescriptionController::class, 'pharmacyIndex']);
        }
    );

    Route::middleware('nurse')->group(function (){
        Route::get('/monitoring-sheets/my-latest-filled', [\App\Http\Controllers\StatisticsController::class, 'nurseFilledMonitoringSheets']);
        Route::get('/monitoring-sheets/total-filled', [\App\Http\Controllers\StatisticsController::class, 'nurseTotalFilledMonitoringSheets']);
        Route::get('/monitoring-sheets/today-available', [\App\Http\Controllers\StatisticsController::class, 'nurseMonitoringSheetsAvailableToFill']);

    });


    // all can view
    Route::get('/medicines/{medicine}', [\App\Http\Controllers\MedicineController::class, 'medicine']);
    Route::get('/medicines', [\App\Http\Controllers\MedicineController::class, 'index']);

    Route::get('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}/medicine-requests/{request}', [MedicineRequestController::class, 'request']);
    Route::get('/patients/{patient}/medical-records/{medical_record}/prescriptions/{prescription}/medicine-requests', [MedicineRequestController::class, 'index']);















    });

Route::get('/ai-search', [\App\Http\Controllers\AiController::class, 'index']);



