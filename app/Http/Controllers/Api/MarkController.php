<?php

namespace App\Http\Controllers\Api;

use App\Models\Mark;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Resources\MarkResource;

class MarkController extends Controller
{
    public function store(StoreMarkRequest $request)
    {
        $mark = Mark::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'enrollment.student',
        ])->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Mark added successfully',
            'data' => new MarkResource($mark),
        ], 201);
    }

    public function studentMarks()
    {
        $studentId = Auth::id();

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
            'enrollment.student',
        ])
        ->where(
            'enrollment_id',
            $enrollment->id
        )
        ->latest()
        ->get();

        return MarkResource::collection($marks);
    }

    public function teacherMarks()
    {
        $teacherId = Auth::id();

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
        ->get();

        return MarkResource::collection($marks);
    }
}