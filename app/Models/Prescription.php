<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'medical_record_id' , 'user_id'];


    public function medicalRecord() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class , 'medical_record_id');
    }

    public function medicineRequests() : HasMany
    {
        return $this->hasMany(MedicineRequest::class , 'prescription_id');
    }

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
