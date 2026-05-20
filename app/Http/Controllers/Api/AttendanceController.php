<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\StudentEnrollment;
class AttendanceController extends Controller
{
    public function store(StoreAttendanceRequest $request)
    {
        $attendance = Attendance::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => $attendance,
        ], 201);
    }

    public function studentAttendances()
    {
        $studentId = auth()->id();

        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )->latest()->first();

        if (! $enrollment) {

            return response()->json([
                'message' => 'Student is not enrolled'
            ], 404);

        }

        $attendances = Attendance::where(
            'enrollment_id',
            $enrollment->id
        )
        ->orderByDesc('date')
        ->get();

        return response()->json([
            'data' => $attendances,
        ]);
    }

    public function teacherAttendances()
    {
        $teacherId = auth()->id();

        $attendances = Attendance::with([
            'enrollment.student',
            'enrollment.section',
        ])
        ->whereHas(
            'enrollment.section.teacherSubjects',
            function ($query) use ($teacherId) {

                $query->where(
                    'teacher_id',
                    $teacherId
                );

            }
        )
        ->orderByDesc('date')
        ->get();

        return response()->json([
            'data' => $attendances,
        ]);
    }
}