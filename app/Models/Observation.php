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

    protected $fillable = ['name' , 'user_id'];
    public function medicalRecord() : BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
}
