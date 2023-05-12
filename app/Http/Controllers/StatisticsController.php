<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Medicine;
use App\Models\MedicineRequest;

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
    public function doctorPatientsStatistics(): JsonResponse
    {
        $query = Patient::query();

        if (\request()->has('startDate')) {
            $query->whereDate('created_at', '>=', \request()->get('startDate'));
        }
        if (\request()->has('endDate')) {
            $query->whereDate('created_at', '<=', \request()->get('endDate'));
        }
        if (\request()->has('type')) {
            $query = $this->filterByType($query, 'created_at', \request()->get('type'));
        }

        if (\request()->has('of')) {
            if (\request()->of == 'gender') {

                $query = $query->select(DB::raw('DATE(created_at) as date'), 'gender', DB::raw('COUNT(*) as count'))
                    ->groupBy('date', 'gender')
                    ->orderBy('date')
                    ->get();

                $totalCount = 0;
                $maleTotal = 0;
                $femaleTotal = 0;
                $dates = [];
                $maleData = [];
                $femaleData = [];

                foreach ($query as $result) {
                    $date = $result->date;
                    $gender = $result->gender;
                    $count = $result->count;

                    $totalCount += $count;

                    if (!in_array($date, $dates)) {
                        $dates[] = $date;
                    }

                    if ($gender == 'Male') {
                        $maleTotal += $count;

                        if (!isset($maleData[$date])) {
                            $maleData[$date] = 0;
                        }
                        $maleData[$date] += $count;
                    } else {
                        $femaleTotal += $count;

                        if (!isset($femaleData[$date])) {
                            $femaleData[$date] = 0;
                        }
                        $femaleData[$date] += $count;
                    }
                }


                $maleCounts = [];
                $femaleCounts = [];

                foreach ($dates as $date) {
                    $maleCounts[] = isset($maleData[$date]) ? $maleData[$date] : 0;
                    $femaleCounts[] = isset($femaleData[$date]) ? $femaleData[$date] : 0;
                }

                $data = [
                    'lineChart' => [
                        'labels' => $dates,
                        'datasets' => [
                            [
                                'label' => 'Male',
                                'data' => $maleCounts,
                                'backgroundColor' => 'rgba(54, 162, 235)',
                                'borderColor' => 'rgba(54, 162, 235, 1)',
                                'borderWidth' => 1,
                            ],
                            [
                                'label' => 'Female',
                                'data' => $femaleCounts,
                                'backgroundColor' => 'rgba(255, 99, 132)',
                                'borderColor' => 'rgba(255, 99, 132, 1)',
                                'borderWidth' => 5,
                            ],
                        ],
                        'totalCount' => $totalCount,
                        'maleTotal' => $maleTotal,
                        'femaleTotal' => $femaleTotal,
                    ],
                    'pieChart' => [
                        'labels' => ['Male', 'Female'],
                        'datasets' => [
                            [
                                'data' => [$maleTotal, $femaleTotal],
                                'backgroundColor' => ['rgba(54, 162, 235)', 'rgba(255, 99, 132)'],
                                'borderColor' => ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132)'],
                                'borderWidth' => 1,
                            ],
                        ],
                    ],
                ];

                return response()->json($data);
            }
        }
        return response()->json(['message' => 'Invalid request'], Response::HTTP_BAD_REQUEST);
    }

    public function doctorMedicalRecordsStatistics(): JsonResponse
    {
        $query = MedicalRecord::query();

        if ($startDate = request('startDate')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = request('endDate')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $of = request('of');
        $type = request('type');

        if ($of == 'Inpatient') {
            $entryDateColumn = 'patient_entry_date';

            if ($type) {
                $query = $this->filterByType($query, $entryDateColumn, $type);
            }

            $data = $this->getChartDataForDateType($query, $entryDateColumn, 'Inpatient');
        } elseif ($of == 'Outpatient') {
            $leavingDateColumn = 'patient_leaving_date';

            if ($type) {
                $query = $this->filterByType($query, $leavingDateColumn, $type);
            }

            $data = $this->getChartDataForDateType($query, $leavingDateColumn, 'Outpatient');
        } else if ($of == 'Diagnosis') {
            $dateColumn = 'patient_entry_date';
            $diagnosisColumn = 'state_upon_enter';

            if ($type) {
                $query = $this->filterByType($query, 'patient_entry_date', $type);
            }

            $data = $this->getChartDataForOtherTypes($query, $dateColumn, $diagnosisColumn, 'Diagnosis');
        } else {
            return response()->json(['message' => 'Invalid request'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($data);
    }

    public function pharmacistMedicinesStatistics(): JsonResponse
    {
        $query = MedicineRequest::query();

        if ($startDate = request('startDate')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = request('endDate')) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        $of = request('of');
        $type = request('type');
        $id = request('id');
        if ($of == 'Quantity') {
            $dateColumn = 'medicine_requests.created_at';
            $quantityColumn = 'medicine_id';

            if ($type) {
                $query = $this->filterByType($query, $dateColumn, $type);
            }
            $query = $query->select(DB::raw("DATE($dateColumn) as date"), $quantityColumn, 'medicines.name', DB::raw("sum(medicine_requests.quantity) as count"))
                ->join('medicines', 'medicine_requests.medicine_id', '=', 'medicines.id')
                ->where('medicine_requests.status', '=', 'Approved')
                ->where('medicines.id', '=', $id)
                ->groupBy('date', $quantityColumn, 'medicines.name')
                ->orderBy('date')
                ->get();
            $data = $this->getChartDataForOtherTypes($query, $dateColumn, 'name', 'Quantity', queryExist: true);
        }
        return response()->json($data);
    }

    public function nurseMonitoringSheetsStatistics(): JsonResponse
    {
        $query = MonitoringSheet::query();

        if ($startDate = request('startDate')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = request('endDate')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $of = request('of');
        $type = request('type');

        if ($of == "Filled"){
            $dateColumn = 'filling_date';
            $filledColumn = 'filled_by_id';

            if ($type) {
                $query = $this->filterByType($query, $dateColumn, $type);
            }
            $query = $query->select(DB::raw("DATE($dateColumn) as date"), $filledColumn, DB::raw("count(*) as count"))
                ->where('filled_by_id', '=', auth()->user()->id)
                ->groupBy('date', $filledColumn)
                ->orderBy('date')
                ->get();

            $data = $this->getChartDataForOtherTypes($query, $dateColumn, 'name', 'Quantity', queryExist: true);

        }
        return response()->json($data);
    }
    private function filterByType($query, $dateColumn, $type)
    {
        switch ($type) {
            case 'day':
                $query->whereDate($dateColumn, '=', now()->format('Y-m-d'));
                break;
            case 'week':
                $query->whereBetween($dateColumn, [
                    now()->subWeek()->startOfWeek()->format('Y-m-d'),
                    now()->addDay()->format('Y-m-d')
                ]);
                break;
            case 'month':
                $query->whereBetween($dateColumn, [
                    now()->subDays(30)->format('Y-m-d'),
                    now()->addDay()->format('Y-m-d')
                ]);
                break;
            case 'year':
                $query->whereYear($dateColumn, '=', now()->year);
                break;
            case 'all':
                // no filtering required
                break;
            default:
                abort(Response::HTTP_BAD_REQUEST, 'Invalid request');
        }

        return $query;
    }
    private function getChartDataForOtherTypes($query, $dateColumn, $diagnosisColumn, $label, $aggregate = 'count', $aggregateColumn = '*', $queryExist = false)
    {
        if (!$queryExist) {
            $query = $query->select(DB::raw("DATE($dateColumn) as date"), $diagnosisColumn, DB::raw("$aggregate($aggregateColumn) as count"))
                ->groupBy('date', $diagnosisColumn)
                ->orderBy('date')
                ->get();
        }
        $totalCount = 0;
        $stateCounts = [];
        $dates = [];

        foreach ($query as $result) {
            $date = $result->date;
            $state = $result[$diagnosisColumn];
            $count = $result->count;

            $totalCount += $count;

            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }

            if (!isset($stateCounts[$state])) {
                $stateCounts[$state] = [];
            }

            if (!isset($stateCounts[$state][$date])) {
                $stateCounts[$state][$date] = 0;
            }
            $stateCounts[$state][$date] += $count;
        }

        $stateData = [];
        $statePieData = [];
        $statePieData[] = [];
        $labels = [];
        foreach ($stateCounts as $state => $data) {
            $red = random_int(0, 255);
            $green = random_int(0, 255);
            $blue = random_int(0, 255);

            $counts = [];
            foreach ($dates as $date) {
                $counts[] = isset($data[$date]) ? $data[$date] : 0;
            }
            $stateData[] = [
                'label' => $state,
                'data' => $counts,
                'backgroundColor' => "rgba($red, $green, $blue, 0.2)",
                'borderColor' => "rgba($red, $green, $blue, 1)",
                'borderWidth' => 1,
            ];
            $statePieData[0]['data'][] = array_sum($counts);
            $statePieData[0]['backgroundColor'][] = "rgba($red, $green, $blue, 0.2)";
            $statePieData[0]['borderColor'][] = "rgba($red, $green, $blue, 1)";


            $labels[] = $state;
        }
        $statePieData[0]['borderWidth'] = 1;
        $data = [
            'lineChart' => [
                'labels' => $dates,
                'datasets' => $stateData,
                'totalCount' => $totalCount,
            ],
            'pieChart' => [
                'labels' => $labels,
                'datasets' => $statePieData,
            ],
        ];

        return $data;
    }
    private function getChartDataForDateType($query, $dateColumn, $label)
    {
        $query = $query->select(DB::raw("DATE($dateColumn) as date"), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalCount = 0;
        $dataCount = 0;
        $dates = [];
        $data = [];
        $bgColors = [];
        $borderColors = [];

        foreach ($query as $result) {
            $date = $result->date;
            $count = $result->count;

            $totalCount += $count;
            $dataCount += $count;

            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }


            $data[] = ['date' => $date, 'count' => $count];
        }
        if ($dateColumn === 'patient_entry_date') {
            $bgColors[] = 'rgba(54, 162, 235, 0.5)';
            $borderColors[] = 'rgba(54, 162, 235, 1)';
        } else {
            $bgColors[] = 'rgba(255, 99, 132, 0.5)';
            $borderColors[] = 'rgba(255, 99, 132, 1)';
        }
        return [
            'lineChart' => [
                'labels' => $dates,
                'datasets' => [
                    [
                        'label' => $label,
                        'data' => array_column($data, 'count'),
                        'backgroundColor' => $bgColors,
                        'borderColor' => $borderColors,
                        'borderWidth' => 1,
                    ],
                ],
                'totalCount' => $totalCount,
            ],
            'pieChart' => [
                'labels' => [$label],
                'datasets' => [
                    [
                        'data' => [$totalCount],
                        'backgroundColor' => ['rgba(0, 0, 255, 0.2)'],
                        'borderColor' => ['rgba(0, 0, 255, 1)'],
                        'borderWidth' => 1,
                    ],
                ],
            ],
        ];
    }
}
