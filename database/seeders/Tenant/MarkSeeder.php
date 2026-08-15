<?php

namespace Database\Seeders\Tenant;

use App\Models\Mark;
use App\Models\StudentEnrollment;
use App\Models\TeacherSubject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MarkSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = StudentEnrollment::with('section')
            ->get();

        foreach ($enrollments as $enrollment) {

            $teacherSubjects = TeacherSubject::where(
                'section_id',
                $enrollment->section_id
            )->get();

            foreach ($teacherSubjects as $teacherSubject) {

                foreach ([1, 2] as $term) {

                    foreach (['midterm', 'final'] as $type) {

                        Mark::updateOrCreate(
                            [
                                'enrollment_id' => $enrollment->id,
                                'teacher_subject_id' => $teacherSubject->id,
                                'term' => $term,
                                'type' => $type,
                            ],
                            [
                                'score' => $type === 'midterm'
                                    ? 75
                                    : 85,

                                'max_score' => 100,

                                'exam_date' => Carbon::now()
                                    ->subMonths($term)
                                    ->toDateString(),
                            ]
                        );
                    }
                }
            }
        }
    }
}