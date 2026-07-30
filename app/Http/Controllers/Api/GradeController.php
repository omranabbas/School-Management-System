<?php

namespace App\Http\Controllers\Api;

use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\GradeResource;
use App\Traits\ApiResponse;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->authorizeResource(Grade::class, 'grade');
    }

public function index(Request $request)
{
    $grades = Grade::with('supervisor')
            ->withCount('sections')
            ->withCount(['enrollments as students_count'])
        ->when($request->filled('stage'), function ($query) use ($request) {
            $query->whereHas(
                'supervisor.supervisorProfile',
                fn ($q) => $q->where('stage', $request->stage)
            );
        })
        ->get();

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


        //  $request->validate([
        //     'name' => 'required|string|unique:grades,name',
        // ]);

        // $grade = Auth::user()->supervisedGrades()->create([
        //     'name' => $request->name,
        // ]);

   
            return $this->successResponse(
            new GradeResource(
                $grade->load('supervisor')
                    ->loadCount('sections')
                    ->loadCount(['enrollments as students_count'])
            ),
            'Grade created successfully',
            201
        );
    }

    public function show(Grade $grade)
    {
        $grade->load('supervisor');
        $grade->loadCount('sections');
        $grade->loadCount(['enrollments as students_count']);

        return $this->successResponse(
            new GradeResource($grade),
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
                    ->loadCount('sections')
                    ->loadCount(['enrollments as students_count'])
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