<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;

class PrescriptionPolicy
{
    /**
     * Create a new policy instance.
     */

    public function belongings(User $user , Prescription $prescription , Patient $patient , MedicalRecord $medicalRecord) : bool
    {

        return $prescription->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id;

    }

    public function index(User $user , Patient $patient , MedicalRecord $medicalRecord): bool
    {
        return $medicalRecord->patient_id == $patient->id ;
    }

    public function create(User $user , Patient $patient , MedicalRecord $medicalRecord): bool
    {
        return $medicalRecord->patient_id == $patient->id && $user->id == $medicalRecord->user_id;
    }

    public function updateOrDelete(User $user, Prescription $prescription, Patient $patient , MedicalRecord $medicalRecord): bool
    {
        return $prescription->medical_record_id == $medicalRecord->id && $medicalRecord->patient_id == $patient->id && $user->isDoctor() && $user->id == $medicalRecord->user_id;
    }


}
