<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prescription>
 */
class PrescriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicalRecord = $this->faker->randomElement(\App\Models\MedicalRecord::all());
        $doctor = User::where('role' , 'doctor')->inRandomOrder()->first();
        return [
            'medical_record_id' => $medicalRecord->id,
            'user_id' => $doctor->id,
        ];
    }
}
