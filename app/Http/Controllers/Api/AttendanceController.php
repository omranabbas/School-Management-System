<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Traits\ApiResponse;

class AttendanceController extends Controller
{
    use ApiResponse;

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('create', Attendance::class);

        $attendance = Attendance::create(
            $request->validated()
        );

        $attendance->load([
            'enrollment.student',
            'enrollment.section',
        ]);

        return $this->successResponse(
            new AttendanceResource($attendance),
            'Attendance recorded successfully',
            201
        );
    }

    public function update(
        UpdateAttendanceRequest $request,
        Attendance $attendance
    ) {
        $this->authorize('update', $attendance);

        $attendance->update(
            $request->validated()
        );

        $attendance->load([
            'enrollment.student',
            'enrollment.section',
        ]);

        return $this->successResponse(
            new AttendanceResource($attendance),
            'Attendance updated successfully'
        );
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);

        $attendance->delete();

        return $this->successResponse(
            null,
            'Attendance deleted successfully'
        );
    }

    public function studentAttendances()
    {
        $studentId = Auth::id();

        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )->latest()->first();

        if (! $enrollment) {

            return $this->errorResponse(
                'Student is not enrolled',
                404
            );

        }

        $perPage = request('per_page', 15);

        $attendances = Attendance::with([
            'enrollment.student',
            'enrollment.section',
        ])
        ->where(
            'enrollment_id',
            $enrollment->id
        )
        ->latest()
        ->paginate($perPage);

        return $this->successResponse(
            AttendanceResource::collection($attendances),
            'Attendances fetched successfully'
        );
    }

    public function supervisorAttendances()
    {
        $supervisorId = Auth::id();

        $perPage = request('per_page', 15);

        $attendances = Attendance::with([
            'enrollment.student',
            'enrollment.section',
        ])
        ->whereHas(
            'enrollment.section.grade',
            function ($query) use ($supervisorId) {

                $query->where(
                    'supervisor_id',
                    $supervisorId
                );

            }
        )
        ->latest()
        ->paginate($perPage);

        return $this->successResponse(
            AttendanceResource::collection($attendances),
            'Attendances fetched successfully'
        );
    }
}