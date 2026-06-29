<?php

namespace App\Http\Controllers\Api;

use App\Models\TeacherSubject;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherSubjectRequest;
use App\Http\Resources\TeacherSubjectResource;

class TeacherSubjectController extends Controller
{
    use ApiResponse;

    public function store(StoreTeacherSubjectRequest $request)
    {
        $teacherSubject = TeacherSubject::create(
            $request->validated()
        );

        $teacherSubject->load([
            'teacher',
            'subject',
            'section.grade',
            'academicYear',
        ]);

        return $this->successResponse(
            new TeacherSubjectResource($teacherSubject),
            'Teacher assigned successfully',
            201
        );
    }
}