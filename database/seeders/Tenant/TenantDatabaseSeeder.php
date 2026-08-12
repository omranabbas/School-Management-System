<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Database\Seeders\Tenant\UserSeeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AcademicYearSeeder::class,
            AcademicStructureSeeder::class,
            EnrollmentSeeder::class,
            TeacherSubjectSeeder::class,
            ScheduleSeeder::class,
            AttendanceSeeder::class,
            MarkSeeder::class,
        ]);
    }
}