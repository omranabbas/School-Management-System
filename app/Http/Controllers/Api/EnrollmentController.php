<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\StudentEnrollment;

class EnrollmentController extends Controller
{
    public function store(StoreEnrollmentRequest $request)
    {
        $enrollment = StudentEnrollment::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Student enrolled successfully',
            'data' => $enrollment,
        ], 201);
    }
}