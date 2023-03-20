<?php

namespace Database\Seeders;

use App\Models\MedicineRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MedicineRequest::factory()->count(200)->create();

    }
}
