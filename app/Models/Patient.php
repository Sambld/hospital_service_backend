<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $all)
 * @method static find($id)
 * @method static paginate()
 * @method static withCount(string $string)
 */
class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'place_of_birth',
        'gender',
        'address',
        'nationality',
        'phone_number',
        'family_situation',
        'emergency_contact_name',
        'emergency_contact_number',
    ];

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }
}
