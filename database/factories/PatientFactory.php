<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'birth_date' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'place_of_birth' => fake()->city,
            'gender' => fake()->randomElement(['Male', 'Female']),
            'address' => fake()->address,
            'nationality' => fake()->country,
            'phone_number' => fake()->phoneNumber,
            'family_situation' => fake()->randomElement(['Single', 'Married', 'Divorced']),
            'emergency_contact_name' => fake()->name,
            'emergency_contact_number' => fake()->phoneNumber,
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now(),
        ];
    }
}
