<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MedicalRecord extends Model
{
    use HasFactory;


    public function patient() : BelongsTo {
        return $this->belongsTo(Patient::class);
    }

    public function assigned_doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function complementary_examinations() : HasMany
    {
        return $this->hasMany(ComplementaryExamination::class );
    }
    public function medicine_requests() : HasMany
    {
        return $this->hasMany(MedicineRequest::class , 'record_id');
    }
    public function observations() : HasMany
    {
        return $this->hasMany(Observation::class);
    }

    public function monitoring_sheets() : HasMany
    {
        return $this->hasMany(MonitoringSheet::class , 'record_id');
    }
}
