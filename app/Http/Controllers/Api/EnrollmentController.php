<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\StudentEnrollment;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Resources\EnrollmentResource;

class EnrollmentController extends Controller
{
    use ApiResponse;

    public function store(StoreEnrollmentRequest $request)
    {
        $enrollment = StudentEnrollment::create(
            $request->validated()
        );

        $enrollment->load([
            'student',
            'section.grade',
            'academicYear',
        ]);

        return $this->successResponse(
            new EnrollmentResource($enrollment),
            'Student enrolled successfully',
            201
        );
    }
}