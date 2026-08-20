<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherAbsenceRequest;
use App\Http\Requests\UpdateTeacherAbsenceRequest;
use App\Http\Requests\UpdateTeacherAbsenceStatusRequest;
use App\Http\Resources\TeacherAbsenceResource;
use App\Notifications\TeacherAbsenceCreatedNotification;
use App\Notifications\TeacherAbsenceStatusUpdatedNotification;
use App\Models\Schedule;
use App\Models\TeacherSubject;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TeacherAbsence;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TeacherAbsenceController extends Controller
{
    use ApiResponse;

  public function index(Request $request)
    {
        $absences = TeacherAbsence::with([
            'teacher',
            'replacementTeacher',
        ])
        ->when($request->teacher_id, function ($query) use ($request) {
            $query->where('teacher_id', $request->teacher_id);
        })
        ->latest()
        ->get();

        return $this->successResponse(
            TeacherAbsenceResource::collection($absences),
            'Teacher absences retrieved successfully.'
        );
    }

    public function store(StoreTeacherAbsenceRequest $request)
    {
        $this->authorize('create', TeacherAbsence::class);

        $absence = TeacherAbsence::create([
            'teacher_id' => $request->user()->id,
            'absence_date' => $request->absence_date,
            'reason' => $request->reason,
            'replacement_teacher_id' => $request->replacement_teacher_id,
        ]);

        $absence->refresh();

        $absence->load([
            'teacher',
            'replacementTeacher',
        ]);

        $absence->teacher->notify(
            new TeacherAbsenceCreatedNotification($absence)
        );

        return $this->successResponse(
            new TeacherAbsenceResource($absence),
            'Teacher absence created successfully.',
            201
        );
    }

    public function show(TeacherAbsence $teacherAbsence)
    {
        $this->authorize('view', $teacherAbsence);

        return $this->successResponse(
            new TeacherAbsenceResource(
                $teacherAbsence->load([
                    'teacher',
                    'replacementTeacher',
                ])
            ),
            'Teacher absence retrieved successfully.'
        );
    }

    public function update(
        UpdateTeacherAbsenceRequest $request,
        TeacherAbsence $teacherAbsence
    ) {
        $this->authorize('update', $teacherAbsence);

        $teacherAbsence->update($request->validated());

        return $this->successResponse(
            new TeacherAbsenceResource(
                $teacherAbsence->load([
                    'teacher',
                    'replacementTeacher',
                ])
            ),
            'Teacher absence updated successfully.'
        );
    }

    public function destroy(TeacherAbsence $teacherAbsence)
    {
        $this->authorize('delete', $teacherAbsence);

        $teacherAbsence->delete();

        return $this->successResponse(
            null,
            'Teacher absence deleted successfully.'
        );
    }

    public function updateStatus(
        UpdateTeacherAbsenceStatusRequest $request,
        TeacherAbsence $teacherAbsence
    ) {
        $this->authorize('approve', $teacherAbsence);



            $teacherAbsence->update([
                'status' => $request->status,
                'replacement_teacher_id' => $request->replacement_teacher_id,
            ]);

            $teacherAbsence->teacher->notify(
                new TeacherAbsenceStatusUpdatedNotification($teacherAbsence->fresh())
            );

        return $this->successResponse(
            new TeacherAbsenceResource(
                $teacherAbsence->fresh()->load([
                    'teacher',
                    'replacementTeacher',
                ])
            ),
            'Teacher absence approved successfully.'
        );
    }
}