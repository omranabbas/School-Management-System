<?php

namespace Database\Seeders\Tenant;

use App\Models\Schedule;
use App\Models\TeacherSubject;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
        ];

        $teacherSubjects = TeacherSubject::orderBy('id')->get();

        foreach ($teacherSubjects as $teacherSubject) {

            foreach ($days as $day) {

                for ($period = 1; $period <= 5; $period++) {

                    $startHour = 7 + $period;

                    Schedule::updateOrCreate(
                        [
                            'teacher_subject_id' => $teacherSubject->id,
                            'day' => $day,
                            'period' => $period,
                        ],
                        [
                            'start_time' => sprintf(
                                '%02d:00:00',
                                $startHour
                            ),
                            'end_time' => sprintf(
                                '%02d:45:00',
                                $startHour
                            ),
                        ]
                    );
                }
            }
        }
    }
}