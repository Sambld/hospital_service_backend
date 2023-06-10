<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ComplementaryExamination extends Model
{
    use HasFactory;

    protected $fillable = ['type' , 'result' , 'user_id'];
    public function medicalRecord() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
