<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\MedicalRecord;
use App\Models\Observation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ImagePolicy
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
    public function view(User $user, Image $image): bool
    {
        //
    }
    public function belongings(User $user , Patient $patient ,MedicalRecord $medicalRecord,Observation $observation): bool
    {
//        dd($complementaryExamination, $patient, $medicalRecord);
        return $observation->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user , Patient $patient , MedicalRecord $medicalRecord , Observation $observation): bool
    {
//        dd($user,$patient, $medicalRecord, $observation);
        return $medicalRecord->user_id == $user->id && $observation->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Image $image): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Image $image , MedicalRecord $medicalRecord , Observation $observation): bool
    {
        return $image->observation_id == $observation->id && $observation->medical_record_id == $medicalRecord->id && $medicalRecord->user_id == $user->id ;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Image $image): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Image $image): bool
    {
        //
    }
}
