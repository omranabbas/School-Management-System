<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use App\Models\StudentEnrollment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;

class ScheduleController extends Controller
{
    public function store(StoreScheduleRequest $request)
    {
        $schedule = Schedule::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Schedule created successfully',
            'data' => $schedule,
        ], 201);
    }
    
    public function teacherSchedule()
    {
        $teacherId = auth()->id();

        $schedules = \App\Models\Schedule::with([
            'teacherSubject.subject',
            'teacherSubject.section',
        ])
        ->whereHas('teacherSubject', function ($query) use ($teacherId) {

            $query->where('teacher_id', $teacherId);

        })
        ->orderBy('day')
        ->orderBy('period')
        ->get();

        return response()->json([
            'data' => $schedules,
        ]);
    }

    public function studentSchedule()
    {
        $studentId = auth()->id();

        $enrollment =StudentEnrollment::where(
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
        ])
        ->whereHas('teacherSubject', function ($query) use ($sectionId) {

            $query->where('section_id', $sectionId);

        })
        ->orderBy('day')
        ->orderBy('period')
        ->get();

        return response()->json([
            'data' => $schedules,
        ]);
    }
}