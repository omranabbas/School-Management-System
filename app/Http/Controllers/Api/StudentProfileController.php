<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class StudentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('role:supervisor')
            ->only(['store', 'update']);
    }

    public function index()
    {
        return StudentProfile::with('student')->get();
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id', 'unique:student_profiles,student_id'],
            'phone' => ['required', 'string'],
            'parent_phone' => ['required', 'string'],
            'personal_image' => ['required', 'image'],
        ]);
        if (User::find($request->student_id)->role !== 'student') {
            return response()->json([
                'message' => 'Selected user is not a student.'
            ], 422);
        }

        if ($request->hasFile('personal_image')) {
            $validated['personal_image'] =
                $request->file('personal_image')->store("tenants/" . tenant()->id . "/students/personal_images", 'public');
        }

        $profile = StudentProfile::create($validated);

        return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentProfile $studentProfile)
    {
        return $studentProfile->load('student');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentProfile $studentProfile)
    {
        $validated = $request->validate([
            'phone' => ['sometimes', 'string'],
            'parent_phone' => ['sometimes', 'string'],
            'personal_image' => ['sometimes', 'image'],
        ]);

        if ($request->hasFile('personal_image')) {

            if ($studentProfile->personal_image) {
                Storage::disk('public')
                    ->delete($studentProfile->personal_image);
            }

            $validated['personal_image'] =
                $request->file('personal_image')->store("tenants/" . tenant()->id . "/students", 'public');
        }

        $studentProfile->update($validated);

        return response()->json($studentProfile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentProfile $studentProfile) {}
}
