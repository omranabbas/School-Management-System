<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use App\Models\Schedule;
use App\Models\StudentEnrollment;
use App\Services\ScheduleService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\AcademicYear;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function store(StoreScheduleRequest $request)
    {
        $this->authorize('create', Schedule::class);

        $schedule = Schedule::create(
            $request->validated()
        );

        $schedule->load([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ]);

        return $this->successResponse(
            new ScheduleResource($schedule),
            'Schedule created successfully',
            201
        );
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $schedule->update(
            $request->validated()
        );

        $schedule->load([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ]);

        return $this->successResponse(
            new ScheduleResource($schedule),
            'Schedule updated successfully'
        );
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return $this->successResponse(
            null,
            'Schedule deleted successfully'
        );
    }

    public function teacherSchedule()
    {
        $teacherId = Auth::id();


        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
            'teacherSubject.subject.grade'
        ])
            ->whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orderByRaw("
    FIELD(
        day,
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday'
    )
")
            ->orderBy('period')
            ->get();


        return $this->successResponse(
            ScheduleResource::collection($schedules),
            'Schedules fetched successfully'
        );
    }

    public function studentSchedule()
    {
        $studentId = Auth::id();

        $activeAcademicYear = AcademicYear::where('is_active',true)->first() ?? AcademicYear::latest()->first();

        $enrollment = StudentEnrollment::where('student_id', $studentId)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->first();

        if (! $enrollment) {

            return $this->errorResponse(
                'Student is not enrolled',
                404
            );
        }

        $sectionId = $enrollment->section_id;

        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ])
            ->whereHas('teacherSubject', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->orderByRaw("
    FIELD(
        day,
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday'
    )
")->orderBy('period')
            ->get();


        return $this->successResponse(
            ScheduleResource::collection($schedules),
            'Schedules fetched successfully'
        );
    }

    public function supervisorSchedule()
    {
        $supervisorId = Auth::id();

        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ])
            ->whereHas(
                'teacherSubject.section.grade',
                function ($query) use ($supervisorId) {
                    $query->where('supervisor_id', $supervisorId);
                }
            )
            ->when(request()->filled('section_id'), function ($query) {
                $query->whereHas(
                    'teacherSubject.section',
                    function ($query) {
                        $query->where(
                            'sections.id',
                            request('section_id')
                        );
                    }
                );
            })
            ->orderByRaw("
            FIELD(
                day,
                'saturday',
                'sunday',
                'monday',
                'tuesday',
                'wednesday',
                'thursday'
            )
        ")
            ->orderBy('period')
            ->get();

        return $this->successResponse(
            ScheduleResource::collection($schedules),
            'Schedules fetched successfully'
        );
    }
}
