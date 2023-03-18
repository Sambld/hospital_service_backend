<?php

namespace App\Policies;

use App\Models\MandatoryDeclaration;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MandatoryDeclarationPolicy
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
    public function view(User $user, MandatoryDeclaration $mandatoryDeclaration): bool
    {
        //
    }

    public function belongings(User $user ,Patient $patient, MedicalRecord $medicalRecord ) : bool
    {
        return $medicalRecord->patient_id == $patient->id;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user , MedicalRecord $medicalRecord): bool
    {
        return  $user->id == $medicalRecord->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        return  $user->id == $medicalRecord->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        return  $user->id == $medicalRecord->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MandatoryDeclaration $mandatoryDeclaration): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MandatoryDeclaration $mandatoryDeclaration): bool
    {
        //
    }
}
