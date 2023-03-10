<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Observation extends Model
{
    use HasFactory;
    public function medical_record() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }
}
