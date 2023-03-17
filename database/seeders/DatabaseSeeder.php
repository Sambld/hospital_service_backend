<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ComplementaryExamination;
use App\Models\Image;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\MonitoringSheet;
use App\Models\Observation;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Patient::factory(20)->create();
        User::factory(6)->create();
        $sam = new User(['first_name' => 'sam' , 'last_name' => 'samo' , 'username' => 'sam' , 'password' => bcrypt('samisamo') , 'role' => 'doctor']);
        $sam->save();
        Medicine::factory(100)->create();
        MedicalRecord::factory(30)->create();
        MonitoringSheet::factory(200)->has(Treatment::factory()->count(5))->create();
        ComplementaryExamination::factory(10)->create();
        Observation::factory(10)->create();
        Image::factory(10)->create();
        MedicineRequest::factory()->count(20)->create();






    }
}
