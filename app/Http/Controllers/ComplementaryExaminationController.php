<?php

namespace App\Http\Controllers;

use App\Models\ComplementaryExamination;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class ComplementaryExaminationController extends Controller
{
    //

    public function examination(Patient $patient , MedicalRecord $medicalRecord ,ComplementaryExamination $complementaryExamination) : JsonResponse
    {
        return response()->json($complementaryExamination);
    }
}
