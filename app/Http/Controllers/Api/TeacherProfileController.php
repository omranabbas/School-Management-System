<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TeacherProfile;
use App\Models\User;
class TeacherProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:supervisor')
            ->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TeacherProfile::with('teacher')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'exists:users,id',
                'unique:teacher_profiles,teacher_id',
            ],
            'years_experience' => [
                'required',
                'integer',
                'min:0',
            ],
            'certificate_image' => [
                'required',
                'image',
            ],
        ]);

        if (User::find($request->teacher_id)->role !== 'teacher') {
            return response()->json([
                'message' => 'Selected user is not a teacher.'
            ], 422);
        }

        if ($request->hasFile('certificate_image')) {

            $validated['certificate_image'] =
                $request->file('certificate_image')->store(
                    "tenants/" . tenant()->id . "/teachers/certificates_images",
                    'public'
                );
        }

        $profile = TeacherProfile::create($validated);

        return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherProfile $teacherProfile)
    {
        return $teacherProfile->load('teacher');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherProfile $teacherProfile)
    {
        $validated = $request->validate([
            'years_experience' => [
                'sometimes',
                'integer',
                'min:0',
            ],
            'certificate_image' => [
                'sometimes',
                'image',
            ],
        ]);

        if ($request->hasFile('certificate_image')) {

            if ($teacherProfile->certificate_image) {
                Storage::disk('public')
                    ->delete($teacherProfile->certificate_image);
            }

            $validated['certificate_image'] =
                $request->file('certificate_image')->store(
                    "tenants/" . tenant()->id . "/teachers/certificates_images",
                    'public'
                );
        }

        $teacherProfile->update($validated);

        return response()->json($teacherProfile);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherProfile $teacherProfile) {}
}
