<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'is_pharmaceutical',
        'expiration_date',
        'supplier',
        'manufacturer'
    ];

    public function medicineRequests() : BelongsToMany
    {
        return $this->belongsToMany(MedicineRequest::class , 'medicine_request_medicine');
    }

    public function treatments() : HasMany
    {
        return $this->hasMany(Treatment::class);
    }
}
