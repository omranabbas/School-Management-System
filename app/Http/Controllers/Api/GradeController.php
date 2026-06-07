<?php

namespace App\Http\Controllers\Api;

use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\GradeResource;
use App\Traits\ApiResponse;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;

class GradeController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(Grade::class, 'grade');
    }

    public function index()
    {
        $grades = Grade::with('supervisor')->get();

        return $this->successResponse(
            GradeResource::collection($grades),
            'Grades fetched successfully'
        );
    }

    public function store(StoreGradeRequest $request)
    {
        $grade = Auth::user()->supervisedGrades()->create(
                $request->validated()
            );

            return $this->successResponse(
            new GradeResource(
                $grade->load('supervisor')
            ),
            'Grade created successfully',
            201
        );
    }

    public function show(Grade $grade)
    {
        return $this->successResponse(
            new GradeResource(
                $grade->load('supervisor')
            ),
            'Grade fetched successfully'
        );
    }

    public function update(
        UpdateGradeRequest $request,
        Grade $grade
    ) {
        $grade->update(
            $request->validated()
        );

        return $this->successResponse(
            new GradeResource(
                $grade->load('supervisor')
            ),
            'Grade updated successfully'
        );
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return $this->successResponse(
            null,
            'Grade deleted successfully'
        );
    }
}