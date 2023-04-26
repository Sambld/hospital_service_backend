<?php

namespace Database\Seeders;

use App\Models\MonitoringSheet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonitoringSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // generate 1000 monitoring sheets
        MonitoringSheet::factory()->count(1000)->create();
    }
}
