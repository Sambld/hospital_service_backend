<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\MonitoringSheet;
use Database\Seeders\MonitoringSheetSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Treatment>
 */
class TreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        $monitoringSheet = MonitoringSheet::inRandomOrder()->first();
//        $medicine = Medicine::inRandomOrder()->first();
        $sheet = MonitoringSheet::inRandomOrder()->first();
        $medicine = Medicine::inRandomOrder()->first();
        return [
            'monitoring_sheet_id' => $sheet->id,
            'medicine_id' => $medicine->id,
            'name' => $medicine->name,
            'dose' => fake()->numberBetween(1, 10) . ' ' . fake()->randomElement(['mg', 'g', 'mL']),
            'type' => fake()->randomElement(['Oral', 'Injection', 'Topical']),
        ];
    }
}
