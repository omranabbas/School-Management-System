<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherProfileResource;
use App\Http\Requests\StoreTeacherProfileRequest;
use App\Http\Requests\UpdateTeacherProfileRequest;

class TeacherProfileController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('role:supervisor')
            ->only(['store', 'update']);
    }

    public function index()
    {
        $profiles = TeacherProfile::with('teacher')->get();

        return $this->successResponse(
            TeacherProfileResource::collection($profiles),
            'Teacher profiles fetched successfully'
        );
    }

    public function store(StoreTeacherProfileRequest $request)
    {
        $validated = $request->validated();

        if (User::find($validated['teacher_id'])->role !== 'teacher') {
            return $this->errorResponse(
                'Selected user is not a teacher.',
                422
            );
        }

        if ($request->hasFile('certificate_image')) {
            $validated['certificate_image'] = $request->file('certificate_image')
                ->store(
                    'tenants/' . tenant()->id . '/teachers/certificates_images',
                    'public'
                );
        }

        $profile = TeacherProfile::create($validated);

        return $this->successResponse(
            new TeacherProfileResource(
                $profile->load('teacher')
            ),
            'Teacher profile created successfully',
            201
        );
    }

    public function show(TeacherProfile $teacherProfile)
    {
        return $this->successResponse(
            new TeacherProfileResource(
                $teacherProfile->load('teacher')
            ),
            'Teacher profile fetched successfully'
        );
    }

    public function update(
        UpdateTeacherProfileRequest $request,
        TeacherProfile $teacherProfile
    ) {
        $validated = $request->validated();

        if ($request->hasFile('certificate_image')) {

            if ($teacherProfile->certificate_image) {
                Storage::disk('public')
                    ->delete($teacherProfile->certificate_image);
            }

            $validated['certificate_image'] = $request->file('certificate_image')
                ->store(
                    'tenants/' . tenant()->id . '/teachers/certificates_images',
                    'public'
                );
        }

        $teacherProfile->update($validated);

        return $this->successResponse(
            new TeacherProfileResource(
                $teacherProfile->fresh()->load('teacher')
            ),
            'Teacher profile updated successfully'
        );
    }

    public function destroy(TeacherProfile $teacherProfile)
    {
        if ($teacherProfile->certificate_image) {
            Storage::disk('public')
                ->delete($teacherProfile->certificate_image);
        }

        $teacherProfile->delete();

        return $this->successResponse(
            null,
            'Teacher profile deleted successfully'
        );
    }
}