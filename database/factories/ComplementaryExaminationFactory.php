<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComplementaryExamination>
 */
class ComplementaryExaminationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $record = MedicalRecord::inRandomOrder()->first();
        $doctor = User::where('role' , 'doctor')->inRandomOrder()->first();
        return [
            'type' => fake()->randomElement(['X-ray', 'MRI', 'CT scan', 'Ultrasound']),
            'medical_record_id' => $record->id ,
            'result' => fake()->sentence,
            'user_id' => $doctor->id
        ];
    }
}
