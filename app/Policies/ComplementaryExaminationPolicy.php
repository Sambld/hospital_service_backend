<?php

namespace App\Policies;

use App\Models\ComplementaryExamination;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComplementaryExaminationPolicy
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
    public function view(User $user, ComplementaryExamination $complementaryExamination): bool
    {
        //
    }

    public function belongings(User $user , ComplementaryExamination $complementaryExamination , Patient $patient , MedicalRecord $medicalRecord): bool
    {
//        dd($complementaryExamination, $patient, $medicalRecord);
        return $complementaryExamination->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user , Patient $patient , MedicalRecord $medicalRecord): bool
    {
//        return $medicalRecord->patient_id == $patient->id;
          return $user->isDoctor();

    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateOrDelete(User $user, ComplementaryExamination $complementaryExamination, Patient $patient , MedicalRecord $medicalRecord): bool
    {
       return $complementaryExamination->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id && $user->isDoctor() && $user->id == $complementaryExamination->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ComplementaryExamination $complementaryExamination): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ComplementaryExamination $complementaryExamination): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ComplementaryExamination $complementaryExamination): bool
    {
        //
    }
}
