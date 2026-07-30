<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Section;
use App\Models\StudentEnrollment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('role:supervisor')
            ->only(['store', 'update', 'destroy']);
    }
 public function index(Request $request)
{
    $query = StudentEnrollment::query();

    if ($request->filled('section_id')) {
        $query->where('section_id', $request->section_id);
    }

    if ($request->filled('academic_year_id')) {
        $query->where('academic_year_id', $request->academic_year_id);
    }

    if ($request->filled('grade_id')) {
        $query->whereHas('section', function ($query) use ($request) {
            $query->where('grade_id', $request->grade_id);
        });
    }

    $enrollments = $query->get();

    return $this->successResponse(
        EnrollmentResource::collection($enrollments),
        'Student enrollments fetched successfully'
    );
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('users', 'id'),
            ],

            'section_id' => [
                'required',
                Rule::exists('sections', 'id'),
            ],

            'academic_year_id' => [
                'required',
                Rule::exists('academic_years', 'id'),
            ],
        ]);

        $section = Section::findOrFail($request->section_id);
        if ($section->grade->supervisor_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not allowed to enroll students in this section.',
                403
            );
        }

        $enrollment = StudentEnrollment::create($validated);

        return $this->successResponse(
            $enrollment,
            'Student enrollment created successfully',
            201
        );
    }

    public function show(StudentEnrollment $enrollment)
    {


        return $this->successResponse(
            new EnrollmentResource($enrollment),
            'Student enrollment fetched successfully'
        );
    }

    public function update(Request $request, StudentEnrollment $enrollment)
    {
        $validated = $request->validate([
            'student_id' => [
                'sometimes',
                Rule::exists('users', 'id'),
            ],

            'section_id' => [
                'sometimes',
                Rule::exists('sections', 'id'),
            ],

            'academic_year_id' => [
                'sometimes',
                Rule::exists('academic_years', 'id'),
            ],
        ]);
        if ( $enrollment->section->grade->supervisor_id !== Auth::id()
        ) {
            return $this->errorResponse(
                'You are not allowed to update this enrollment.',
                403
            );
        }

        if ($request->filled('section_id')) {
            $section = Section::with('grade')->findOrFail($request->section_id);

            if (
                $section->grade->supervisor_id !== Auth::id()
                || $enrollment->section->grade->supervisor_id !== Auth::id()
            ) {
                return $this->errorResponse(
                    'You are not allowed to update this enrollment.',
                    403
                );
            }
        }
        $enrollment->update($validated);

        return $this->successResponse(
            $enrollment,
            'Student enrollment updated successfully'
        );
    }

    public function destroy(StudentEnrollment $enrollment)
    {

        if (
            $enrollment->section->grade->supervisor_id !== Auth::id()
        ) {
            return $this->errorResponse(
                'You are not allowed to delete this enrollment.',
                403
            );
        }
        $enrollment->delete();

        return $this->successResponse(
            null,
            'Student enrollment deleted successfully'
        );
    }
}
