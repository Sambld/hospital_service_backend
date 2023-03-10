<?php

namespace Database\Factories;

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
        $user = User::inRandomOrder()->first();
        $record = $user->medical_records()->inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'record_id' => $record->id,
            'quantity' => fake()->numberBetween(1, 10),
            'status' => fake()->randomElement(['Pending', 'Approved', 'Rejected']),
            'review' => fake()->sentence,
        ];
    }
}
