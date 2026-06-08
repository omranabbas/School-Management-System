<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\StudentEnrollment;
use App\Traits\ApiResponse;

class EnrollmentController extends Controller
{
    use ApiResponse;

    public function store(StoreEnrollmentRequest $request)
    {
        $enrollment = StudentEnrollment::create(
            $request->validated()
        );

        return $this->successResponse(
            $enrollment,
            'Student enrolled successfully',
            201
        );
    }
}