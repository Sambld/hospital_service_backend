<?php

namespace Database\Factories;

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
        return [
            'medical_record_id' => $medicalRecord->id,
            'name' => $this->faker->word,
        ];
    }
}