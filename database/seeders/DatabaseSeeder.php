<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ComplementaryExamination;
use App\Models\Image;
use App\Models\MandatoryDeclaration;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\MedicineRequest;
use App\Models\MonitoringSheet;
use App\Models\Observation;
use App\Models\Patient;
use App\Models\Prescription;
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

        Patient::factory(50)->create();
        User::factory(10)->create();
        // create 5 doctors
        User::factory(5)->create(['role' => 'doctor']);
        $sam = new User(['first_name' => 'sam' , 'last_name' => 'samo' , 'username' => 'sam' , 'password' => bcrypt('samisamo') , 'role' => 'doctor']);
        $sam->save();
        $nurse = new User(['first_name' => 'nurse' , 'last_name' => '1' , 'username' => 'nurse' , 'password' => bcrypt('pass') , 'role' => 'nurse']);
        $nurse->save();
        $admin = new User(['first_name' => 'admin' , 'last_name' => '1' , 'username' => 'admin' , 'password' => bcrypt('pass') , 'role' => 'administrator']);
        $admin->save();
        $doctor = new User(['first_name' => 'doctor' , 'last_name' => '1' , 'username' => 'doctor' , 'password' => bcrypt('pass') , 'role' => 'doctor']);
        $doctor->save();
        $pharmasict = new User(['first_name' => 'pharmacist' , 'last_name' => '1' , 'username' => 'pharmacist' , 'password' => bcrypt('pass') , 'role' => 'pharmacist']);
        $pharmasict->save();

        Medicine::factory(100)->create();
        MedicalRecord::factory(200)->has(MandatoryDeclaration::factory())->create();

        // loop through each patient medical records and make only one active
        foreach (Patient::all() as $patient) {
            $medicalRecords = $patient->medicalRecords;
            // if there is no medical records for this patient
            if ($medicalRecords->count() > 0) {
                if (fake()->boolean(20)) {
                    $medicalRecords->last()->update(['patient_leaving_date' => null , 'state_upon_exit' => null]);
                }
            }


        }




        MonitoringSheet::factory(500)->has(Treatment::factory()->count(5))->create();
        ComplementaryExamination::factory(300)->create();
        Observation::factory(300)->hasImages(random_int(1,4))->create();
        Prescription::factory(200)->has(MedicineRequest::factory()->count(random_int(2,4)))->create();
//        MedicineRequest::factory()->count(2000)->create();






    }
}
