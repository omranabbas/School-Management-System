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
use App\Notifications\TeacherAssignedNotification;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class TeacherSubjectController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(TeacherSubject::class, 'teacher_subject');
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
        $subject = Subject::findOrFail($request->subject_id);
        $section = Section::findOrFail($request->section_id);
        if ($subject->grade->supervisor_id !== Auth::id() || $section->grade->supervisor_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not authorized to assign teachers for this subject.',
                403
            );
        }
        if ($subject->grade_id !== $section->grade_id) {
            return $this->errorResponse(
                'The selected subject does not belong to the selected section.',
                422
            );
        }
        $teacherSubject = TeacherSubject::create(
            $request->validated()
        );

        $teacherSubject->load([
            'teacher',
            'subject',
            'section.grade',
            'academicYear',
        ]);
        

        $teacherSubject->teacher->notify(
            new TeacherAssignedNotification($teacherSubject)
        );

        return $this->successResponse(
            new TeacherSubjectResource($teacherSubject),
            'Teacher assigned successfully',
            201
        );
    }

    public function show(TeacherSubject $teacher_subject)
    {
        $teacher_subject->load([
            'teacher',
            'subject',
            'section.grade',
            'academicYear',
        ]);

        return $this->successResponse(
            new TeacherSubjectResource($teacher_subject),
            'Teacher assignment fetched successfully'
        );
    }

   public function update(
    UpdateTeacherSubjectRequest $request,
    TeacherSubject $teacher_subject
) {
    $subjectId = $request->subject_id ?? $teacher_subject->subject_id;
    $sectionId = $request->section_id ?? $teacher_subject->section_id;

    $subject = Subject::findOrFail($subjectId);
    $section = Section::findOrFail($sectionId);

    if ($subject->grade_id !== $section->grade_id) {
        return $this->errorResponse(
            'The selected subject does not belong to the selected section.',
            422
        );
    }

    if ($subject->grade->supervisor_id !== Auth::id()) {
        return $this->errorResponse(
            'You are not authorized to assign teachers for this subject.',
            403
        );
    }

    if ($section->grade->supervisor_id !== Auth::id()) {
        return $this->errorResponse(
            'You are not authorized to assign teachers for this section.',
            403
        );
    }

    $teacher_subject->update(
        $request->validated()
    );

    $teacher_subject->load([
        'teacher',
        'subject',
        'section.grade',
        'academicYear',
    ]);

    return $this->successResponse(
        new TeacherSubjectResource($teacher_subject),
        'Teacher assignment updated successfully'
    );
}

    public function destroy(TeacherSubject $teacher_subject)
    {
        try {

            $teacher_subject->delete();

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
