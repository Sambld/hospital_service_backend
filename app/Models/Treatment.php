<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Treatment extends Model
{
    use HasFactory;


    public function monitoring_sheet() : BelongsTo
    {
        return $this->belongsTo(MonitoringSheet::class , 'monitoring_sheet_id');
    }
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
