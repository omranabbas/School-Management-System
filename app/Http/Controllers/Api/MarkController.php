<?php

namespace App\Http\Controllers\Api;

use App\Models\Mark;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Http\Resources\MarkResource;
use App\Traits\ApiResponse;

class MarkController extends Controller
{
    use ApiResponse;

    public function show(Mark $mark)
    {
        $this->authorize('view', $mark);

        $mark->load([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'enrollment.student',
        ]);

        return $this->successResponse(
            new MarkResource($mark),
            'Mark fetched successfully'
        );
    }

    public function store(StoreMarkRequest $request)
    {
        $this->authorize('create', Mark::class);

        $mark = Mark::create(
            $request->validated()
        );

        $mark->load([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'enrollment.student',
        ]);

        return $this->successResponse(
            new MarkResource($mark),
            'Mark added successfully',
            201
        );
    }

    public function update(
        UpdateMarkRequest $request,
        Mark $mark
    ) {
        $this->authorize('update', $mark);

        $mark->update(
            $request->validated()
        );

        $mark->load([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'enrollment.student',
        ]);

        return $this->successResponse(
            new MarkResource($mark),
            'Mark updated successfully'
        );
    }

    public function destroy(Mark $mark)
    {
        $this->authorize('delete', $mark);

        $mark->delete();

        return $this->successResponse(
            null,
            'Mark deleted successfully'
        );
    }

    public function studentMarks()
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

        $marks = Mark::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'enrollment.student',
        ])
        ->where(
            'enrollment_id',
            $enrollment->id
        )
        ->latest()
        ->paginate($perPage);

        return $this->successResponse(
            MarkResource::collection($marks),
            'Marks fetched successfully'
        );
    }

    public function teacherMarks()
    {
        $teacherId = Auth::id();

        $perPage = request('per_page', 15);

        $marks = Mark::with([
            'enrollment.student',
            'teacherSubject.subject',
            'teacherSubject.teacher',
        ])
        ->whereHas(
            'teacherSubject',
            function ($query) use ($teacherId) {

                $query->where(
                    'teacher_id',
                    $teacherId
                );

            }
        )
        ->latest()
        ->paginate($perPage);

        return $this->successResponse(
            MarkResource::collection($marks),
            'Marks fetched successfully'
        );
    }
}