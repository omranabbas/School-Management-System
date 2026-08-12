<?php

namespace Database\Seeders\Tenant;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::updateOrCreate(
            ['name' => 2025],
            ['is_active' => true]
        );

        AcademicYear::updateOrCreate(
            ['name' => 2026],
            ['is_active' => false]
        );
    }
}