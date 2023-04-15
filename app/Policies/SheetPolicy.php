<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SheetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient, MedicalRecord $medicalRecord): bool
    {
        return $patient->id == $medicalRecord->patient_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Patient $patient, MedicalRecord $medicalRecord): bool
    {
        error_log('create');
//        dd($patient,$medicalRecord);
        return $patient->id == $medicalRecord->patient_id && $user->id == $medicalRecord->user_id;
    }


    public function belongings(User $user, MonitoringSheet $monitoringSheet, Patient $patient, MedicalRecord $medicalRecord): bool
    {
        error_log('belong');
//        dd($monitoringSheet, $patient, $medicalRecord);
        return $patient->id == $medicalRecord->patient_id && $monitoringSheet->record_id == $medicalRecord->id;

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MonitoringSheet $monitoringSheet, Patient $patient, MedicalRecord $medicalRecord)
    {
        if ($monitoringSheet->isFilled()) {
            return ($user->id == $medicalRecord->user_id || $user->id == $monitoringSheet->filled_by_id);
        } else {
            if ($user->role == 'doctor') return $user->id == $medicalRecord->user_id;
        }
        return true;


    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MonitoringSheet $monitoringSheet, MedicalRecord $medicalRecord): bool
    {
        return $user->isDoctor() && $user->id == $medicalRecord->user_id && $monitoringSheet->record_id == $medicalRecord->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MonitoringSheet $monitoringSheet): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MonitoringSheet $monitoringSheet): bool
    {
        //
    }
}
