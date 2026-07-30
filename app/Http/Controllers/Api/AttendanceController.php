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
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    use ApiResponse;

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('create', Attendance::class);
        $enrollment = StudentEnrollment::find($request->enrollment_id);
        if ($enrollment->section->grade->supervisor_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not authorized to record attendance for this student.',
                403
            );
        }
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
        if($request->filled('enrollment_id')){
        $enrollment = StudentEnrollment::find($request->enrollment_id);
        if ($enrollment && $enrollment->section->grade->supervisor_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not authorized to record attendance for this student.',
                403
            );
        }}
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
    public function studentAttendancesById($studentId, Request $request)
    {
        $request->validate(['academic_year_id' => [
            'required',
            Rule::exists('academic_years', 'id'),
        ],]);
        $academicYearId = $request->academic_year_id;
        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )
            ->when($academicYearId, function ($query) use ($academicYearId) {
                $query->where(
                    'academic_year_id',
                    $academicYearId
                );
            })
            ->first();

        if (! $enrollment) {
            return $this->errorResponse(
                'Student enrollment not found',
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
    public function studentAttendances(Request $request)
    {
        $studentId = Auth::id();
        $request->validate(['academic_year_id' => [
            'required',
            Rule::exists('academic_years', 'id'),
        ],]);
        $academicYearId = $request->academic_year_id;
        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )
            ->when($academicYearId, function ($query) use ($academicYearId) {
                $query->where(
                    'academic_year_id',
                    $academicYearId
                );
            })
            ->first();

        if (! $enrollment) {
            return $this->errorResponse(
                'Student enrollment not found',
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
