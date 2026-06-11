<?php

namespace App\Http\Controllers\Api;

use App\Models\Grade;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Traits\ApiResponse;
class SectionController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(Section::class, 'section');
    }

    public function index()
    {
        $sections = Section::with('grade')->get();

        return $this->successResponse(
            SectionResource::collection($sections),
            'Sections fetched successfully'
        );
    }

    public function store(StoreSectionRequest $request)
    {
        $grade = Grade::findOrFail(
            $request->grade_id
        );

        if ($grade->supervisor_id !== Auth::id()) {

            return $this->errorResponse(
                'You are not allowed to create a section for this grade.',
                403
            );

        }

        $section = Section::create(
            $request->validated()
        );

        return $this->successResponse(
            new SectionResource(
                $section->load('grade')
            ),
            'Section created successfully',
            201
        );
    }

    public function show(Section $section)
    {
        return $this->successResponse(
            new SectionResource(
                $section->load('grade')
            ),
            'Section fetched successfully'
        );
    }

    public function update(
        UpdateSectionRequest $request,
        Section $section
    ) {
        $validated = $request->validated();

        if (isset($validated['grade_id'])) {

            $grade = Grade::findOrFail(
                $validated['grade_id']
            );

            if ($grade->supervisor_id !== Auth::id()) {

                return $this->errorResponse(
                    'You are not allowed to move this section to that grade.',
                    403
                );
            }
        }

        $section->update($validated);

        return $this->successResponse(
            new SectionResource(
                $section->load('grade')
            ),
            'Section updated successfully'
        );
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return $this->successResponse(
            null,
            'Section deleted successfully'
        );
    }
}