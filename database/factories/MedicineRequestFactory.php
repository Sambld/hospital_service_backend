<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicineRequest>
 */
class MedicineRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::where('role', 'doctor')->inRandomOrder()->first();
//        $prescription = $user->medicalRecords()->inRandomOrder()->first()->prescriptions()->inRandomOrder()->first();
        $medicine = Medicine::inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'prescription_id' => Prescription::class,
            'medicine_id' => $medicine->id,
            'quantity' => fake()->numberBetween(1, 10),
            'status' => fake()->randomElement(['Pending', 'Approved', 'Rejected']),
            'review' => fake()->sentence,
        ];
    }
}
