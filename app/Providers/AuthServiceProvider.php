<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\MedicalRecord;
use App\Models\MonitoringSheet;
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

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
