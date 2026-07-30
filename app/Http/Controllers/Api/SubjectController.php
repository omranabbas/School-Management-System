<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Grade;
use Illuminate\Database\QueryException;
use App\Models\Subject;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(Subject::class, 'subject');
    }

public function index(Request $request)
{
    $query = Subject::with('grade');

    if ($request->filled('grade_id')) {
        $query->where('grade_id', $request->grade_id);
    }

    return $this->successResponse(
        SubjectResource::collection($query->get()),
        'Subjects fetched successfully'
    );
}

    public function store(StoreSubjectRequest $request)
    {
        $grade = Grade::findOrFail($request->grade_id);
        if ($grade->supervisor_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not authorized to create a subject for this grade.',
                403
            );
        }

        $subject = Subject::create(
            $request->validated()
        );

        $subject->load('grade');

        return $this->successResponse(
            new SubjectResource($subject),
            'Subject created successfully',
            201
        );
    }

    public function show(Subject $subject)
    {
        $subject->load('grade');

        return $this->successResponse(
            new SubjectResource($subject),
            'Subject fetched successfully'
        );
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        if($request->filled('grade_id')){
        $grade = Grade::findOrFail($request->grade_id);
        if ($grade->supervisor_id !== Auth::id()) {
            return $this->errorResponse(
                'You are not authorized to create a subject for this grade.',
                403
            );
        }}
        $subject->update(
            $request->validated()
        );

        $subject->load('grade');

        return $this->successResponse(
            new SubjectResource($subject),
            'Subject updated successfully'
        );
    }



    public function destroy(Subject $subject)
    {
        try {

            $subject->delete();

            return $this->successResponse(
                null,
                'Subject deleted successfully'
            );
        } catch (QueryException $e) {

            return $this->errorResponse(
                'Cannot delete this subject because it is assigned to one or more teachers.',
                409
            );
        }
    }
}
