<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicineList = file('medicine_names.txt', FILE_IGNORE_NEW_LINES);
        $medicineName = $medicineList[array_rand($medicineList)];
        $medicineNameParts = explode(':', $medicineName);
        $name = trim($medicineNameParts[0]);
        $category = trim($medicineNameParts[1]);

        return [
            'name' => $name,
            'category' => $category,
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 100),
            'quantity' => fake()->numberBetween(1, 100),
            'is_pharmaceutical' => fake()->boolean(),
            'manufacturer' => fake()->company(),
            'supplier' => fake()->name(),
            'expiration_date' => fake()->dateTimeBetween('now', '+5 years')->format('Y-m-d'),
        ];
    }
}
