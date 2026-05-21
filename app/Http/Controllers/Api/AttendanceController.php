<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Resources\AttendanceResource;

class AttendanceController extends Controller
{
    public function store(StoreAttendanceRequest $request)
    {
        $attendance = Attendance::create(
            $request->validated()
        );

        $attendance->load([
            'enrollment.student',
            'enrollment.section',
        ]);

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => new AttendanceResource($attendance),
        ], 201);
    }

    public function studentAttendances()
    {
        $studentId = Auth::id();

        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )->latest()->first();

        if (! $enrollment) {

            return response()->json([
                'message' => 'Student is not enrolled'
            ], 404);

        }

        $attendances = Attendance::with([
            'enrollment.student',
            'enrollment.section',
        ])
        ->where(
            'enrollment_id',
            $enrollment->id
        )
        ->latest()
        ->get();

        return AttendanceResource::collection(
            $attendances
        );
    }

    public function teacherAttendances()
    {
        $teacherId = Auth::id();

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
        ->latest()
        ->get();

        return AttendanceResource::collection(
            $attendances
        );
    }
}