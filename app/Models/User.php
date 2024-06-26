<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
    public function medicineRequests(): HasMany
    {
        return $this->hasMany(MedicineRequest::class);
    }
    public function filledMonitoringSheets(): HasMany
    {
        return $this->hasMany(MonitoringSheet::class);
    }


    public function isDoctor(){return $this->role == 'doctor';}
    public function isAdmin(){return $this->role == 'administrator';}
    public function isNurse(){return $this->role == 'nurse';}
    public function isPharm(){return $this->role == 'pharmacist';}

    public function fullname()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function monitoringSheets(): HasMany
    {
        return $this->hasMany(MonitoringSheet::class);
    }

    public function ovservations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }






}
