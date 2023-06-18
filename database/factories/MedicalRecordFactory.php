<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patient = Patient::inRandomOrder()->first();
        $user = User::where('role' , 'doctor')->inRandomOrder()->first();

        $patientEntryDate = fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d');
        // patient leaving date is between patient entry date and now
        $patientLeavingDate = fake()->dateTimeBetween($patientEntryDate, 'now')->format('Y-m-d');

        return [
            //
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'medical_specialty' => 'infectious diseases',
            'condition_description' => fake()->sentence(),
            'state_upon_enter' => fake()->randomElement(['stable', 'critical', 'serious']),
            'standard_treatment' => fake()->sentence(),
            'state_upon_exit' => fake()->randomElement(['recovered', 'improved', 'stable']),
            'bed_number' => fake()->numberBetween(1, 100),
            'patient_entry_date' => $patientEntryDate,
            'patient_leaving_date' => $patientLeavingDate ,
        ];
    }
}
