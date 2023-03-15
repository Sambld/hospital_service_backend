<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicalRecordPolicy
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
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        //
    }

    public function belongings(User $user, MedicalRecord $medicalRecord , Patient $patient) : bool
    {
        return  $medicalRecord->patient_id == $patient->id;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
//        error_log('can update?');
//        dd($user, $medicalRecord);
        return $user->id == $medicalRecord->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        return $user->id == $medicalRecord->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecord $medicalRecord): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
    {
        //
    }
}
