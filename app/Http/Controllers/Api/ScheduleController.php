<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
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

        return response()->json([
            'message' => 'Schedule created successfully',
            'data' => new ScheduleResource($schedule),
        ], 201);
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

        return response()->json([
            'message' => 'Schedule updated successfully',
            'data' => new ScheduleResource($schedule),
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return response()->json([
            'message' => 'Schedule deleted successfully',
        ]);
    }

    public function teacherSchedule()
    {
        
        $teacherId = Auth::id();

        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ])
            ->whereHas('teacherSubject', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orderByRaw("
                FIELD(
                    day,
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday'
                )
            ")
            ->orderBy('period')
            ->get();

        return ScheduleResource::collection($schedules);
    }

    public function studentSchedule()
    {
        $studentId = Auth::id();

        $enrollment = StudentEnrollment::where(
            'student_id',
            $studentId
        )->latest()->first();

        if (! $enrollment) {

            return response()->json([
                'message' => 'Student is not enrolled'
            ], 404);

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
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday'
                )
            ")
            ->orderBy('period')
            ->get();

        return ScheduleResource::collection($schedules);
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
                    $query->where(
                        'supervisor_id',
                        $supervisorId
                    );
                }
            )
            ->orderByRaw("
                FIELD(
                    day,
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday'
                )
            ")
            ->orderBy('period')
            ->get();

        return ScheduleResource::collection($schedules);
    }
}