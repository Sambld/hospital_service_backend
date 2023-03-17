<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Observation;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TreatmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user,Patient $patient , MedicalRecord $medicalRecord , MonitoringSheet $monitoringSheet): bool
    {
        return $medicalRecord->patient_id == $patient->id && $medicalRecord->id == $monitoringSheet->record_id ;
    }
    public function belongings(User $user , Treatment $treatment , Patient $patient , MedicalRecord $medicalRecord , MonitoringSheet $monitoringSheet): bool
    {
//        dd($complementaryExamination, $patient, $medicalRecord);
        return $medicalRecord->patient_id == $patient->id && $medicalRecord->id == $monitoringSheet->record_id && $monitoringSheet->id == $treatment->monitoring_sheet_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Observation $observation): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user , Patient $patient , MedicalRecord $medicalRecord , MonitoringSheet $monitoringSheet): bool
    {
//        error_log('observation create');
//        dd($patient, $medicalRecord,$monitoringSheet);
        return $medicalRecord->patient_id == $patient->id && $monitoringSheet->record_id == $medicalRecord->id && $user->id == $medicalRecord->user_id;


    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateOrDelete(User $user , Treatment $treatment , Patient $patient , MedicalRecord $medicalRecord , MonitoringSheet $monitoringSheet): bool
    {
//        error_log('update;');
        return $medicalRecord->patient_id == $patient->id && $medicalRecord->id == $monitoringSheet->record_id && $monitoringSheet->id == $treatment->monitoring_sheet_id && $user->id == $medicalRecord->user_id && $user->isDoctor();

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Observation $observation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Observation $observation): bool
    {
        //
    }
}
