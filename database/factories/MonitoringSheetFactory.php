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

        $record = MedicalRecord::inRandomOrder()->first();
        $doctor = User::where('role' , 'doctor')->inRandomOrder()->first();
        $nurse = User::where('role' , 'nurse')->inRandomOrder()->first();


        if ($record->isClosed()){
            $fillingDate = fake()->dateTimeBetween($record->patient_entry_date, $record->patient_leaving_date)->format('Y-m-d H:m');
        }
        else{
            $fillingDate = fake()->dateTimeBetween($record->patient_entry_date, 'now')->format('Y-m-d H:m');
        }

        $isFilled = $record->isClosed() || fake()->boolean(60);
        $isClosed = fake()->boolean(10);
        if ($isFilled){
            return [
                'record_id' => $record->id,
                'user_id' => $doctor->id,
                'filled_by_id' => $nurse->id,
                'filling_date' => $fillingDate,
                'urine' => fake()->numberBetween(100, 1000),
                'blood_pressure' => fake()->numberBetween(80, 200). '/' . fake()->numberBetween(50, 150),
                'weight' => fake()->numberBetween(40, 200),
                'temperature' => fake()->numberBetween(35, 42),
                'progress_report' => fake()->paragraph(nbSentences: 1),
            ];
        }else{
            return [
                'record_id' => $record->id,
                'user_id' => $doctor->id,
                'filled_by_id' => null ,
                'filling_date' => $fillingDate,
                'urine' => null,
                'blood_pressure' => null,
                'weight' => null,
                'temperature' => null,
                'progress_report' => null,
            ];
        }





//            'updated_by_id' => null ,
    }
}
