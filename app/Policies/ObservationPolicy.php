<?php

namespace App\Policies;

use App\Models\ComplementaryExamination;
use App\Models\MedicalRecord;
use App\Models\Observation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }
    public function belongings(User $user , Observation $observation , Patient $patient , MedicalRecord $medicalRecord): bool
    {
//        dd($complementaryExamination, $patient, $medicalRecord);
        return $observation->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id;
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
    public function create(User $user , Patient $patient , MedicalRecord $medicalRecord): bool
    {
//        error_log('observation create');
//        dd($patient, $medicalRecord);
        return $medicalRecord->patient_id == $patient->id;


    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateOrDelete(User $user, Observation $observation, Patient $patient , MedicalRecord $medicalRecord): bool
    {
        return $observation->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id && $user->isDoctor() && $user->id == $medicalRecord->user_id;
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
