<?php

namespace App\Http\Controllers\Api;

use App\Models\TeacherSubject;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherSubjectRequest;
use App\Http\Requests\UpdateTeacherSubjectRequest;
use App\Http\Resources\TeacherSubjectResource;

class TeacherSubjectController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(TeacherSubject::class, 'teacherSubject');
    }

    public function index(Request $request)
    {
        $query = TeacherSubject::with([
            'teacher',
            'subject',
            'section.grade',
            'academicYear',
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('teacher', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('subject', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });

            });
        }

        $query->when(
            $request->teacher_id,
            fn($q) => $q->where('teacher_id', $request->teacher_id)
        );

        $query->when(
            $request->subject_id,
            fn($q) => $q->where('subject_id', $request->subject_id)
        );

        $query->when(
            $request->section_id,
            fn($q) => $q->where('section_id', $request->section_id)
        );

        $query->when(
            $request->academic_year_id,
            fn($q) => $q->where('academic_year_id', $request->academic_year_id)
        );

        $query->when(
            $request->grade_id,
            function ($q) use ($request) {

                $q->whereHas('section', function ($query) use ($request) {

                    $query->where(
                        'grade_id',
                        $request->grade_id
                    );

                });

            }
        );

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $allowed = [
            'id',
            'teacher_id',
            'subject_id',
            'section_id',
            'academic_year_id',
        ];

        if (in_array($sort, $allowed)) {
            $query->orderBy($sort, $direction);
        }

        $teacherSubjects = $query->paginate(
            $request->get('per_page', 10)
        );

        return $this->successResponse(
            TeacherSubjectResource::collection($teacherSubjects),
            'Teacher assignments fetched successfully'
        );
    }

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

    public function show(TeacherSubject $teacherSubject)
    {
        $teacherSubject->load([
            'teacher',
            'subject',
            'section.grade',
            'academicYear',
        ]);

        return $this->successResponse(
            new TeacherSubjectResource($teacherSubject),
            'Teacher assignment fetched successfully'
        );
    }

    public function update(
        UpdateTeacherSubjectRequest $request,
        TeacherSubject $teacherSubject
    ) {

        $teacherSubject->update(
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
            'Teacher assignment updated successfully'
        );
    }

    public function destroy(TeacherSubject $teacherSubject)
    {
        try {

            $teacherSubject->delete();

            return $this->successResponse(
                null,
                'Teacher assignment deleted successfully'
            );

        } catch (QueryException $e) {

            return $this->errorResponse(
                'Cannot delete this assignment because it is used by other records.',
                409
            );

        }
    }
}