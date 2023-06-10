<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\MedicineRequest;
use App\Models\Observation;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Faker\Provider\Medical;
use Illuminate\Auth\Access\Response;

class MedicineRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user,Patient $patient, MedicalRecord $medicalRecord , Prescription $prescription): bool
    {
       return  $patient->id == $medicalRecord->patient_id && $prescription->medical_record_id == $medicalRecord->id  && ( $user->isDoctor() || $user->isPharm());
    }

    public function belongings(User $user , MedicineRequest $medicineRequest , Patient $patient , MedicalRecord $medicalRecord , Prescription $prescription): bool
    {
//        dd($complementaryExamination, $patient, $medicalRecord);
        return $medicineRequest->prescription_id == $prescription->id && $prescription->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id;
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicineRequest $medicineRequest ,  MedicalRecord $medicalRecord): bool
    {
        return $user->isDoctor() || $user->isPharm() ;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user , Patient $patient, MedicalRecord $medicalRecord , Prescription $prescription): bool
    {
        return  $patient->id == $medicalRecord->patient_id && $user->id == $prescription->user_id && $prescription->medical_record_id == $medicalRecord->id ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user , MedicalRecord $medicalRecord , Prescription $prescription ): bool
    {
        return $user->isPharm() || $prescription->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicineRequest $medicineRequest , MedicalRecord $medicalRecord, Prescription $prescription): bool
    {
        return $user->isPharm() || $prescription->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
//    public function restore(User $user, MedicineRequest $medicineRequest): bool
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     */
//    public function forceDelete(User $user, MedicineRequest $medicineRequest): bool
//    {
//        //
//    }
}
