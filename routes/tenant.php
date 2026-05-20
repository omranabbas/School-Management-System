<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;

use App\Http\Controllers\Api\TeacherSubjectController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\SectionController;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {

    //Auth

    Route::post('/register', RegisterController::class);

    Route::post('/login', LoginController::class);



    Route::middleware('auth:sanctum')->group(function () {

        /*
        Admin Routes
        */

        Route::middleware('role:admin')->group(function () {

            // Grades

            Route::apiResource(
                'grades',
                GradeController::class
            );

            // Sections

            Route::apiResource(
                'sections',
                SectionController::class
            );

            // Teacher Assignments

            Route::post(
                '/teacher-subjects',
                [TeacherSubjectController::class, 'store']
            );

        });

        /*
        Admin & Supervisor Routes
        */

        Route::middleware('role:admin,supervisor')->group(function () {

            // Student Enrollment

            Route::post(
                '/enrollments',
                [EnrollmentController::class, 'store']
            );

        });

        /*
        Supervisor Routes
        */

        Route::middleware('role:supervisor')->group(function () {

            // Schedules

            Route::post(
                '/schedules',
                [ScheduleController::class, 'store']
            );

        });

        /*
        Teacher Routes
        */

        Route::middleware('role:teacher')->group(function () {

            // Marks

            Route::post(
                '/marks',
                [MarkController::class, 'store']
            );

            // Attendance

            Route::post(
                '/attendances',
                [AttendanceController::class, 'store']
            );

            // Teacher Schedules

            Route::get(
                '/teacher-schedules',
                [ScheduleController::class, 'teacherSchedule']
            );

            // Teacher Marks

            Route::get(
                '/teacher-marks',
                [MarkController::class, 'teacherMarks']
            );

            // Teacher Attendance

            Route::get(
                '/teacher-attendances',
                [AttendanceController::class, 'teacherAttendances']
            );

        });

        /*
        Student Routes
        */

        Route::middleware('role:student')->group(function () {

            // Student Schedules

            Route::get(
                '/student-schedules',
                [ScheduleController::class, 'studentSchedule']
            );

            // Student Marks

            Route::get(
                '/student-marks',
                [MarkController::class, 'studentMarks']
            );

            // Student Attendance

            Route::get(
                '/student-attendances',
                [AttendanceController::class, 'studentAttendances']
            );

        });

    });

});