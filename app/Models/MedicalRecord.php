<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static find($medical_record_id)
 */
class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'medical_specialty',
        'condition_description',
        'state_upon_enter',
        'standard_treatment',
        'state_upon_exit',
        'bed_number' ,
        'patient_entry_date',
        'patient_leaving_date',
    ];
    public function patient() : BelongsTo {
        return $this->belongsTo(Patient::class);
    }

    public function assignedDoctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function complementaryExaminations() : HasMany
    {
        return $this->hasMany(ComplementaryExamination::class );
    }
    public function medicineRequests() : HasMany
    {
        return $this->hasMany(MedicineRequest::class , 'record_id');
    }
    public function observations() : HasMany
    {
        return $this->hasMany(Observation::class);
    }

    public function monitoringSheets() : HasMany
    {
        return $this->hasMany(MonitoringSheet::class , 'record_id');
    }

    public function mandatoryDeclaration(): HasOne
    {
        return $this->hasOne(MandatoryDeclaration::class, 'medical_record_id');
    }
}
