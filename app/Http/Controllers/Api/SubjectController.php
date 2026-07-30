<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use Illuminate\Database\QueryException;
use App\Models\Subject;
use App\Traits\ApiResponse;

class SubjectController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(Subject::class, 'subject');
    }

    public function index()
    {
        $subjects = Subject::with('grade')->get();

        return $this->successResponse(
            SubjectResource::collection($subjects),
            'Subjects fetched successfully'
        );
    }

    public function store(StoreSubjectRequest $request)
    {
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