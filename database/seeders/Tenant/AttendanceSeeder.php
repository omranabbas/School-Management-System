<?php

namespace Database\Seeders\Tenant;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\StudentEnrollment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        $enrollments = StudentEnrollment::where(
            'academic_year_id',
            $academicYear->id
        )->get();

        $statuses = [
            'present',
            'present',
            'present',
            'late',
            'absent',
        ];

        foreach ($enrollments as $enrollment) {

            for ($i = 1; $i <= 10; $i++) {

                $date = Carbon::now()
                    ->subDays($i)
                    ->toDateString();

                // جدولك يسمح بسجل حضور واحد فقط للطالب في اليوم
                Attendance::updateOrCreate(
                    [
                        'enrollment_id' => $enrollment->id,
                        'date' => $date,
                    ],
                    [
                        'status' => $statuses[
                            ($enrollment->id + $i) % count($statuses)
                        ],
                    ]
                );
            }
        }
    }
}