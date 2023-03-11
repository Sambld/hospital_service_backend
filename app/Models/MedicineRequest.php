<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MedicineRequest extends Model
{
    use HasFactory;

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function medicalRecord() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class , 'record_id' );
    }

    public function medicines() : BelongsToMany
    {
        return $this->belongsToMany(Medicine::class , 'medicine_request_medicine');
    }
}
