<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringSheetLog extends Model
{
    use HasFactory;


    protected $fillable = [
        'monitoring_sheet_id',
        'user_id',
        'action',
        'type',
        'previous_data',
    ];

    public function monitoringSheet() : BelongsTo
    {
        return $this->belongsTo(MonitoringSheet::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
