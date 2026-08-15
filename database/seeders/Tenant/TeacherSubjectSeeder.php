<?php

namespace Database\Seeders\Tenant;

use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $teachers = User::where('role', 'teacher')
            ->orderBy('id')
            ->get();

        $sections = Section::with('grade')
            ->orderBy('id')
            ->get();

        foreach ($sections as $section) {

            $subjects = $section->grade
                ->subjects()
                ->orderBy('id')
                ->get();

            foreach ($subjects as $index => $subject) {

                $teacher = $teachers[$index % $teachers->count()];

                TeacherSubject::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'section_id' => $section->id,
                        'academic_year_id' => $academicYear->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                    ]
                );
            }
        }
    }
}