<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class MonitoringSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'filling_date',
        'urine',
        'blood_pressure',
        'weight',
        'temperature',
        'progress_report',
        'filled_by_id',
        'user_id',
    ];

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class, 'record_id');
    }

    public function filledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filled_by_id');
    }

    public function isFilled()
    {
        return $this->filled_by_id != null;
    }
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function monitoringSheetLogs() : HasMany
    {
        return $this->hasMany(MonitoringSheetLog::class);
    }







}
