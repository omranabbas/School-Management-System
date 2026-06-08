<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;

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

    // Auth

    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {

        // Logout
        Route::post(
            '/logout',
            LogoutController::class
        );
        // Admin and Supervisor routes

        Route::middleware('role:admin,supervisor')->group(function () {

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

            // Enrollments
            Route::post(
                '/enrollments',
                [EnrollmentController::class, 'store']
            );

            // Teacher Subjects
            Route::post(
                '/teacher-subjects',
                [TeacherSubjectController::class, 'store']
            );
        });

       // Supervisor routes

        Route::middleware('role:supervisor')->group(function () {

           // Schedule
            

            Route::post(
                '/schedules',
                [ScheduleController::class, 'store']
            );

            Route::put(
                '/schedules/{schedule}',
                [ScheduleController::class, 'update']
            );

            Route::delete(
                '/schedules/{schedule}',
                [ScheduleController::class, 'destroy']
            );

            Route::get(
                '/supervisor-schedules',
                [ScheduleController::class, 'supervisorSchedule']
            );

            // Attendance
            

            Route::post(
                '/attendances',
                [AttendanceController::class, 'store']
            );

            Route::put(
                '/attendances/{attendance}',
                [AttendanceController::class, 'update']
            );

            Route::delete(
                '/attendances/{attendance}',
                [AttendanceController::class, 'destroy']
            );

            Route::get(
                '/supervisor-attendances',
                [AttendanceController::class, 'supervisorAttendances']
            );
        });

        // Teacher routes
       

        Route::middleware('role:teacher')->group(function () {

            // Marks
         

            Route::post(
                '/marks',
                [MarkController::class, 'store']
            );

            Route::get(
                '/marks/{mark}',
                [MarkController::class, 'show']
            );

            Route::put(
                '/marks/{mark}',
                [MarkController::class, 'update']
            );

            Route::delete(
                '/marks/{mark}',
                [MarkController::class, 'destroy']
            );

            // Teacher Schedule
            

            Route::get(
                '/teacher-schedules',
                [ScheduleController::class, 'teacherSchedule']
            );

            
            // Teacher Marks
            

            Route::get(
                '/teacher-marks',
                [MarkController::class, 'teacherMarks']
            );
        });

        // Student routes      

        Route::middleware('role:student')->group(function () {

            Route::get(
                '/student-schedules',
                [ScheduleController::class, 'studentSchedule']
            );

            Route::get(
                '/student-marks',
                [MarkController::class, 'studentMarks']
            );

            Route::get(
                '/student-attendances',
                [AttendanceController::class, 'studentAttendances']
            );
        });
    });
});