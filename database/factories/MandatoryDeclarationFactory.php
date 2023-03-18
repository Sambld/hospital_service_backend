<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MandatoryDeclaration>
 */
class MandatoryDeclarationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diagnosis' => $this->faker->sentence,
            'detail' => $this->faker->text,
            'medical_record_id' => MedicalRecord::factory(),
        ];
    }
}
