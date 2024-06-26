<?php

namespace Database\Factories;

use App\Models\ComplementaryExamination;
use App\Models\MedicalRecord;
use App\Models\Observation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        $ce = Observation::inRandomOrder()->first();
        return [
            'path' => 'image.jpg',
            'observation_id' => Observation::factory(),
        ];
    }
}
