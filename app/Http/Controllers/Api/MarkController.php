<?php

namespace App\Http\Controllers\Api;

use App\Models\Mark;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Http\Resources\MarkResource;
use App\Notifications\MarkAddedNotification;
use App\Notifications\MarkUpdatedNotification;
use App\Traits\ApiResponse;
use App\Models\TeacherSubject;

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
            'enrollment.section',
            'enrollment.academicYear'
        ]);

        return $this->successResponse(
            new MarkResource($mark),
            'Mark fetched successfully'
        );
    }

    public function store(StoreMarkRequest $request)
    {   
        $this->authorize('create', Mark::class);

        $enrollment = StudentEnrollment::findOrFail(
            $request->enrollment_id
        );

        $teacherSubject = TeacherSubject::findOrFail(
            $request->teacher_subject_id
        );

        if ($teacherSubject->teacher_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not authorized to add marks for this subject.',
                403
            );
        }

        if ($enrollment->section_id !== $teacherSubject->section_id) {
            return $this->errorResponse(
                'The selected student does not belong to this section.',
                422
            );
        }

        if (
            $enrollment->academic_year_id !==
            $teacherSubject->academic_year_id
        ) {
            return $this->errorResponse(
                'The selected student does not belong to this academic year.',
                422
            );
        }

        $mark = Mark::create(
            $request->validated()
        );

        $mark->load([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'enrollment.student',
            'enrollment.section',
            'enrollment.academicYear',
        ]);

        $mark->enrollment->student->notify(
            new MarkAddedNotification($mark)
        );

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


        $mark->enrollment->student->notify(
            new MarkUpdatedNotification($mark)
        );

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
$enrollmentQuery = StudentEnrollment::where(
        'student_id',
        $studentId
    );

    $enrollmentQuery->when(
        request()->filled('academic_year_id'),
        fn ($q) => $q->where(
            'academic_year_id',
            request('academic_year_id')
        )
    );

    $enrollment = $enrollmentQuery
        ->latest()
        ->first();

    if (! $enrollment) {
        return $this->errorResponse(
            'Student is not enrolled.',
            404
        );
    }

    $query = Mark::with([
        'teacherSubject.subject',
        'teacherSubject.teacher',
        'enrollment.student',
        'enrollment.section',
        'enrollment.academicYear',
    ])->where(
        'enrollment_id',
        $enrollment->id
    );

    $query->when(
        request()->filled('subject_id'),
        function ($q) {
            $q->whereHas(
                'teacherSubject',
                fn ($query) => $query->where(
                    'subject_id',
                    request('subject_id')
                )
            );
        }
    );

    $query->when(
        request()->filled('teacher_id'),
        function ($q) {
            $q->whereHas(
                'teacherSubject',
                fn ($query) => $query->where(
                    'teacher_id',
                    request('teacher_id')
                )
            );
        }
    );

    $query->when(
        request()->filled('term'),
        fn ($q) => $q->where(
            'term',
            request('term')
        )
    );

    $query->when(
        request()->filled('type'),
        fn ($q) => $q->where(
            'type',
            request('type')
        )
    );

    $marks = $query
        ->latest()
        ->paginate(
            request('per_page', 15)
        );

    return $this->successResponse(
        MarkResource::collection($marks),
        'Marks fetched successfully'
    );
}
    public function studentMarksById($studentId)
{
    $enrollmentQuery = StudentEnrollment::where(
        'student_id',
        $studentId
    );

    $enrollmentQuery->when(
        request()->filled('academic_year_id'),
        fn ($q) => $q->where(
            'academic_year_id',
            request('academic_year_id')
        )
    );

    $enrollment = $enrollmentQuery
        ->latest()
        ->first();

    if (! $enrollment) {
        return $this->errorResponse(
            'Student is not enrolled.',
            404
        );
    }

    $query = Mark::with([
        'teacherSubject.subject',
        'teacherSubject.teacher',
        'enrollment.student',
        'enrollment.section',
        'enrollment.academicYear',
    ])->where(
        'enrollment_id',
        $enrollment->id
    );

    $query->when(
        request()->filled('subject_id'),
        function ($q) {
            $q->whereHas(
                'teacherSubject',
                fn ($query) => $query->where(
                    'subject_id',
                    request('subject_id')
                )
            );
        }
    );

    $query->when(
        request()->filled('teacher_id'),
        function ($q) {
            $q->whereHas(
                'teacherSubject',
                fn ($query) => $query->where(
                    'teacher_id',
                    request('teacher_id')
                )
            );
        }
    );

    $query->when(
        request()->filled('term'),
        fn ($q) => $q->where(
            'term',
            request('term')
        )
    );

    $query->when(
        request()->filled('type'),
        fn ($q) => $q->where(
            'type',
            request('type')
        )
    );

    $marks = $query
        ->latest()
        ->paginate(
            request('per_page', 15)
        );

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
