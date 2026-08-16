<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentProfileResource;
use App\Http\Requests\StoreStudentProfileRequest;
use App\Http\Requests\UpdateStudentProfileRequest;

class StudentProfileController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('role:supervisor')
            ->only(['store', 'update']);
    }

    public function index()
    {
        $profiles = StudentProfile::with('student')->get();

        return $this->successResponse(
            StudentProfileResource::collection($profiles),
            'Student profiles fetched successfully'
        );
    }

    public function store(StoreStudentProfileRequest $request)
    {
        $validated = $request->validated();

        if (User::find($validated['student_id'])->role !== 'student') {
            return $this->errorResponse(
                'Selected user is not a student.',
                422
            );
        }



        $profile = StudentProfile::create($validated);

        return $this->successResponse(
            new StudentProfileResource(
                $profile->load('student')
            ),
            'Student profile created successfully',
            201
        );
    }

    public function show(StudentProfile $studentProfile)
    {
        return $this->successResponse(
            new StudentProfileResource(
                $studentProfile->load('student')
            ),
            'Student profile fetched successfully'
        );
    }

    public function update(
        UpdateStudentProfileRequest $request,
        StudentProfile $studentProfile
    ) {
        $validated = $request->validated();

        
        $studentProfile->update($validated);

        return $this->successResponse(
            new StudentProfileResource(
                $studentProfile->fresh()->load('student')
            ),
            'Student profile updated successfully'
        );
    }

    public function destroy(StudentProfile $studentProfile)
    {


        $studentProfile->delete();

        return $this->successResponse(
            null,
            'Student profile deleted successfully'
        );
    }
}