<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorizeResource(Section::class, 'section');
    }
    public function index()
    {
        return Section::with('grade')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
        ]);

        $grade = Grade::findOrFail($request->grade_id);

        if ($grade->supervisor_id !== Auth::user()->id) {
            return response()->json([
                'message' => 'You are not allowed to create a section for this grade.',
            ], 403);
        }

        $section = Section::create([
            'name' => $request->name,
            'grade_id' => $request->grade_id
        ]);

        return response()->json([
            'message' => 'Section created successfully',
            'data' => $section,
        ], 201);
    }

    public function show(Section $section)
    {
        return $section->load('grade');
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'grade_id' => 'sometimes|exists:grades,id',
        ]);
        if ($request->grade_id) {
            $grade = Grade::findOrFail($validated['grade_id']);
              if ($grade->supervisor_id !== Auth::user()->id) {
            return response()->json([
                'message' => 'You are not allowed to create a section for this grade.',
            ], 403);
        }
        }
      
        $section->update($validated);

        return response()->json([
            'message' => 'Section updated successfully',
            'data' => $section,
        ]);
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return response()->json([
            'message' => 'Section deleted successfully',
        ]);
    }
}
