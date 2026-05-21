<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use App\Models\StudentEnrollment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Resources\ScheduleResource;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function store(StoreScheduleRequest $request)
    {
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

    public function teacherSchedule()
    {
        $teacherId = Auth::id();

        $schedules = Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.teacher',
            'teacherSubject.section',
        ])
        ->whereHas(
            'teacherSubject',
            function ($query) use ($teacherId) {

                $query->where(
                    'teacher_id',
                    $teacherId
                );

            }
        )
        ->orderBy('day')
        ->orderBy('period')
        ->get();

        return ScheduleResource::collection(
            $schedules
        );
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
        ->whereHas(
            'teacherSubject',
            function ($query) use ($sectionId) {

                $query->where(
                    'section_id',
                    $sectionId
                );

            }
        )
        ->orderBy('day')
        ->orderBy('period')
        ->get();

        return ScheduleResource::collection(
            $schedules
        );
    }
}