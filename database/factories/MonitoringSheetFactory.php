<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MonitoringSheet>
 */
class MonitoringSheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

//        $medicalRecord = MedicalRecord::inRandomOrder()->first();
        $record = MedicalRecord::inRandomOrder()->first();
//        $staff = Staff::inRandomOrder()->first();
        $staff = User::all()->where('role','nurse')->toQuery()->inRandomOrder()->first();
        return [
            'record_id' => $record->id,
            'filled_by_id' => fake()->boolean(60) ? $staff->id : null ,
            'filling_date' => fake()->dateTimeBetween('now', '+7 days')->format('Y-m-d'),
            'urine' => fake()->numberBetween(100, 1000),
            'blood_pressure' => fake()->numberBetween(80, 200). '/' . fake()->numberBetween(50, 150),
            'weight' => fake()->numberBetween(40, 200),
            'temperature' => fake()->randomFloat(1, 35, 42),
            'progress_report' => fake()->paragraph(nbSentences: 1),
        ];
//            'updated_by_id' => null ,
    }
}
