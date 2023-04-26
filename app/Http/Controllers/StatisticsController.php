<?php

namespace App\Http\Controllers;

use App\Models\MonitoringSheet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    // doctor monitoring sheets latest updates
    public function doctorMonitoringSheetsLatestUpdates()
    {
        $doctor = Auth::user();
        $doctor->load('medicalRecords.monitoringSheets.filledBy');
        // return only the latest 10 filled monitoring sheets in the last 24 hours.
        $monitoringSheets = $doctor->medicalRecords
            ->flatMap(function ($record) {
                return $record->monitoringSheets;
            })
            ->whereNotNull('filled_by_id')
//            ->where('updated_at', '>', now()->subDay())
            ->sortByDesc('updated_at')
            ->take(10)
            ->toArray();

        // add patient name to each monitoring sheet


        return array_values($monitoringSheets);


    }

    // available monitoring sheets to fill for the nurse
    public function nurseMonitoringSheetsAvailableToFill(): JsonResponse
    {
        $monitoringSheets = MonitoringSheet::whereNull('filled_by_id')
            ->where('filling_date', now()->toDateString())
            ->whereHas('medicalRecord', function ($query) {
                $query->whereNull('patient_leaving_date');
            })
            ->orderBy('filling_date', 'desc')
            ->select('id', 'filling_date', 'record_id')
            ->with([
                'medicalRecord' => function ($query) {
                    $query->select('bed_number', 'id', 'patient_id', 'condition_description', 'user_id')->with(['assignedDoctor' => function ($query) {
                        $query->select('id', 'first_name', 'last_name');
                    }, 'patient' => function ($query) {
                        $query->select('id', 'first_name', 'last_name');
                    }]);
                },
            ])
            ->get();
        return response()->json($monitoringSheets);
    }
    // monitoring sheets filled by the nurse
    public function nurseFilledMonitoringSheets(): JsonResponse
    {
        $nurse = Auth::user();
        $monitoringSheets = MonitoringSheet::where('filled_by_id', $nurse->id)
            ->whereNotNull('filled_by_id')
            ->orderBy('updated_at', 'desc')
            ->with([
                'medicalRecord' => function ($query) {
                    $query->select('bed_number', 'id', 'patient_id', 'condition_description', 'user_id')->with(['assignedDoctor' => function ($query) {
                        $query->select('id', 'first_name', 'last_name');
                    }, 'patient' => function ($query) {
                        $query->select('id', 'first_name', 'last_name');
                    }]);
                },
            ])
            ->take(10)
            ->get();
        // return only the latest 10 filled monitoring sheets by the nurse in the last 24 hours.
        return response()->json($monitoringSheets);


    }

    public function nurseTotalFilledMonitoringSheets(): JsonResponse
    {
        $nurse = Auth::user();
        // get total filled monitoring sheets by the nurse
        $monitoringSheetsCount = MonitoringSheet::where('filled_by_id', $nurse->id)
            ->whereNotNull('filled_by_id')
            ->count();
        return response()->json(['count' => $monitoringSheetsCount]);

    }
}
