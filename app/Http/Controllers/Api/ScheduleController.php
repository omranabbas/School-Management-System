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

    public function update(UpdateScheduleRequest $request,Schedule $schedule) 
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

    public function teacherSchedule(ScheduleService $scheduleService)
    {
        $teacherId = Auth::id();

        $date = request('date', now()->toDateString());

        $day = $scheduleService->dayFromDate($date);

        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ])
            ->whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where('day', $day)
            ->orderBy('period')
            ->get();

        $schedules = $scheduleService->applyOverrides($schedules, $date);

        return $this->successResponse(
            ScheduleResource::collection($schedules),
            'Schedules fetched successfully'
        );
    }

    public function studentSchedule(ScheduleService $scheduleService)
    {
        $studentId = Auth::id();

        $date = request('date', now()->toDateString());

        $day = $scheduleService->dayFromDate($date);

        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )->latest()->first();

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
            ->where('day', $day)
            ->orderBy('period')
            ->get();

        $schedules = $scheduleService->applyOverrides($schedules, $date);

        return $this->successResponse(
            ScheduleResource::collection($schedules),
            'Schedules fetched successfully'
        );
    }

    public function supervisorSchedule(ScheduleService $scheduleService)
    {
        $supervisorId = Auth::id();

        $date = request('date', now()->toDateString());

        $day = $scheduleService->dayFromDate($date);

        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ])
            ->whereHas(
                'teacherSubject.section.grade',
                function ($query) use ($supervisorId) {
                    $query->where(
                        'supervisor_id',
                        $supervisorId
                    );
                }
            )
            ->where('day', $day)
            ->orderBy('period')
            ->get();

        $schedules = $scheduleService->applyOverrides($schedules, $date);

        return $this->successResponse(
            ScheduleResource::collection($schedules),
            'Schedules fetched successfully'
        );
    }
}