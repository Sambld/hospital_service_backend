<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ComplementaryExaminationController;
use App\Models\ComplementaryExamination;
use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
use App\Models\Observation;
use App\Policies\ComplementaryExaminationPolicy;
use App\Policies\MedicalRecordPolicy;
use App\Policies\ObservationPolicy;
use App\Policies\SheetPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
            MonitoringSheet::class => SheetPolicy::class,
        MedicalRecord::class => MedicalRecordPolicy::class,
        ComplementaryExamination::class => ComplementaryExaminationPolicy::class,
        Observation::class => ObservationPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
