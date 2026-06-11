<?php

namespace App\Http\Controllers\Api;

use App\Models\TeacherSubject;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherSubjectRequest;
use App\Traits\ApiResponse;

class TeacherSubjectController extends Controller
{
    use ApiResponse;

    public function store(StoreTeacherSubjectRequest $request)
    {
        $teacherSubject = TeacherSubject::create([
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'section_id' => $request->section_id,
            'academic_year_id' => $request->academic_year_id,
        ]);

        return $this->successResponse(
            $teacherSubject,
            'Teacher assigned successfully',
            201
        );
    }
}