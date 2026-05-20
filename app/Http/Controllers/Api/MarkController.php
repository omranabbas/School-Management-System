<?php

namespace App\Http\Controllers\Api;

use App\Models\Mark;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Models\StudentEnrollment;
class MarkController extends Controller
{
    public function store(StoreMarkRequest $request)
    {
        $mark = Mark::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Mark added successfully',
            'data' => $mark,
        ], 201);
    }

    public function studentMarks()
    {
        $studentId = auth()->id();

        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )->latest()->first();

        if (! $enrollment) {

            return response()->json([
                'message' => 'Student is not enrolled'
            ], 404);

        }

        $marks = Mark::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
        ])
        ->where('enrollment_id', $enrollment->id)
        ->get();

        return response()->json([
            'data' => $marks,
        ]);
    }

    public function teacherMarks()
    {
        $teacherId = auth()->id();

        $marks = Mark::with([
            'enrollment.student',
            'teacherSubject.subject',
        ])
        ->whereHas('teacherSubject', function ($query) use ($teacherId) {

            $query->where('teacher_id', $teacherId);

        })
        ->get();

        return response()->json([
            'data' => $marks,
        ]);
    }
}