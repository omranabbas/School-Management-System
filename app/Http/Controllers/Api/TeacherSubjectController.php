<?php

namespace App\Http\Controllers\Api;

use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherSubjectRequest;

class TeacherSubjectController extends Controller
{
    public function store(StoreTeacherSubjectRequest $request)
    {
        $teacherSubject = TeacherSubject::create([
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'section_id' => $request->section_id,
            'academic_year_id' => $request->academic_year_id,
        ]);

        return response()->json([
            'message' => 'Teacher assigned successfully',
            'data' => $teacherSubject,
        ], 201);
    }
}