<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringSheet extends Model
{
    use HasFactory;

    public function medical_record() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class , 'record_id');
    }
    public function filled_by() : BelongsTo
    {
        return $this->belongsTo(User::class , 'filled_by_id');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }
}
