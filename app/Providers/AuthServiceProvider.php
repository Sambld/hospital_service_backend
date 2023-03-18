<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ComplementaryExaminationController;
use App\Models\ComplementaryExamination;
use App\Models\Image;
use App\Models\MandatoryDeclaration;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\MonitoringSheet;
use App\Models\Observation;
use App\Models\Treatment;
use App\Policies\ComplementaryExaminationPolicy;
use App\Policies\ImagePolicy;
use App\Policies\MandatoryDeclarationPolicy;
use App\Policies\MedicalRecordPolicy;
use App\Policies\MedicinePolicy;
use App\Policies\MedicineRequestPolicy;
use App\Policies\ObservationPolicy;
use App\Policies\SheetPolicy;
use App\Policies\TreatmentPolicy;
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
        Treatment::class => TreatmentPolicy::class,
        Medicine::class => MedicinePolicy::class,
        MedicineRequest::class => MedicineRequestPolicy::class,
        Image::class => ImagePolicy::class,
        MandatoryDeclaration::class => MandatoryDeclarationPolicy::class

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
