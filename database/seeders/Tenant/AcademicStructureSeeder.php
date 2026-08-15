<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\SupervisorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AcademicStructureSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = [
            [
                'name' => 2025,
                'is_active' => false,
            ],
            [
                'name' => 2026,
                'is_active' => true,
            ],
        ];

        foreach ($academicYears as $year) {
            AcademicYear::updateOrCreate(
                ['name' => $year['name']],
                ['is_active' => $year['is_active']]
            );
        }

        $academicYear = AcademicYear::where('is_active', true)->first();


        /*
        |--------------------------------------------------------------------------
        | Supervisors
        |--------------------------------------------------------------------------
        */

        $supervisorsData = [
            [
                'name' => 'Ahmad',
                'last_name' => 'Hassan',
                'father_name' => 'Ali',
                'email' => 'supervisor.primary@school.test',
                'stage' => 'primary',
            ],
            [
                'name' => 'Omar',
                'last_name' => 'Khaled',
                'father_name' => 'Mohammad',
                'email' => 'supervisor.preparatory@school.test',
                'stage' => 'preparatory',
            ],
            [
                'name' => 'Yousef',
                'last_name' => 'Mahmoud',
                'father_name' => 'Hassan',
                'email' => 'supervisor.secondary@school.test',
                'stage' => 'secondary',
            ],
        ];

        $supervisors = [];

        foreach ($supervisorsData as $data) {

            $supervisor = User::updateOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'name' => $data['name'],
                    'last_name' => $data['last_name'],
                    'father_name' => $data['father_name'],
                    'role' => 'supervisor',
                    'password' => Hash::make('password'),
                    'date_of_birth' => '1985-01-01',
                ]
            );

            SupervisorProfile::updateOrCreate(
                [
                    'supervisor_id' => $supervisor->id,
                ],
                [
                    'years_experience' => 5,
                    'stage' => $data['stage'],
                ]
            );

            $supervisors[$data['stage']] = $supervisor;
        }


        /*
        |--------------------------------------------------------------------------
        | Grades
        |--------------------------------------------------------------------------
        */

        $grades = [
            [
                'name' => 'Grade 1',
                'stage' => 'primary',
            ],
            [
                'name' => 'Grade 2',
                'stage' => 'primary',
            ],
            [
                'name' => 'Grade 7',
                'stage' => 'preparatory',
            ],
            [
                'name' => 'Grade 8',
                'stage' => 'preparatory',
            ],
            [
                'name' => 'Grade 10',
                'stage' => 'secondary',
            ],
            [
                'name' => 'Grade 11',
                'stage' => 'secondary',
            ],
        ];

        $createdGrades = [];

        foreach ($grades as $gradeData) {

            $grade = Grade::updateOrCreate(
                [
                    'name' => $gradeData['name'],
                ],
                [
                    'supervisor_id' => $supervisors[
                        $gradeData['stage']
                    ]->id,
                ]
            );

            $createdGrades[$gradeData['name']] = $grade;
        }


        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $sections = [
            'Grade 1' => ['A', 'B'],
            'Grade 2' => ['A', 'B'],
            'Grade 7' => ['A', 'B'],
            'Grade 8' => ['A', 'B'],
            'Grade 10' => ['A', 'B'],
            'Grade 11' => ['A', 'B'],
        ];

        foreach ($sections as $gradeName => $sectionNames) {

            $grade = $createdGrades[$gradeName];

            foreach ($sectionNames as $sectionName) {

                Section::updateOrCreate(
                    [
                        'name' => $sectionName,
                        'grade_id' => $grade->id,
                    ],
                    []
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Subjects
        |--------------------------------------------------------------------------
        */

        $subjects = [
            'Grade 1' => [
                'Mathematics',
                'Arabic',
                'English',
                'Science',
            ],

            'Grade 2' => [
                'Mathematics',
                'Arabic',
                'English',
                'Science',
            ],

            'Grade 7' => [
                'Mathematics',
                'Arabic',
                'English',
                'Science',
                'History',
            ],

            'Grade 8' => [
                'Mathematics',
                'Arabic',
                'English',
                'Physics',
                'Chemistry',
            ],

            'Grade 10' => [
                'Mathematics',
                'Arabic',
                'English',
                'Physics',
                'Chemistry',
                'Biology',
            ],

            'Grade 11' => [
                'Mathematics',
                'Arabic',
                'English',
                'Physics',
                'Chemistry',
                'Biology',
            ],
        ];

        foreach ($subjects as $gradeName => $subjectNames) {

            $grade = $createdGrades[$gradeName];

            foreach ($subjectNames as $subjectName) {

                Subject::updateOrCreate(
                    [
                        'name' => $subjectName,
                        'grade_id' => $grade->id,
                    ],
                    []
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Output
        |--------------------------------------------------------------------------
        */

        $this->command?->info(
            'Academic structure seeded successfully.'
        );

        $this->command?->info(
            'Academic Year: ' . $academicYear->name
        );

        $this->command?->info(
            'Supervisors: ' . count($supervisors)
        );

        $this->command?->info(
            'Grades: ' . count($createdGrades)
        );
    }
}