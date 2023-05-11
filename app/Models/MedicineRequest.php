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

    protected $fillable = ['medicine_id' , 'quantity' , 'user_id' , 'record_id' , 'status' , 'review' , 'prescription_id'];
    public function doctor() : BelongsTo
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function prescription() : BelongsTo
    {
        return $this->belongsTo(Prescription::class , 'prescription_id');
    }

    public function medicine() : BelongsTo
    {
        return $this->belongsTo(Medicine::class , 'medicine_id');
    }
}
