<?php

namespace Database\Seeders\Tenant;

use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\StudentEnrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $students = User::where('role', 'student')
            ->orderBy('id')
            ->get();

        $sections = Section::orderBy('id')->get();

        foreach ($students as $index => $student) {

            $section = $sections[$index % $sections->count()];

            StudentEnrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                ],
                [
                    'section_id' => $section->id,
                ]
            );
        }
    }
}