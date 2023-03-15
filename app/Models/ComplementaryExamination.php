<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ComplementaryExamination extends Model
{
    use HasFactory;

    protected $fillable = ['type' , 'result' , 'medical_record_id'];
    public function medicalRecord() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
