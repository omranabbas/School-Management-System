<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\TeacherProfileController;
use App\Http\Controllers\Api\TeacherSubjectController;
use App\Http\Controllers\Api\TeacherAbsenceController;
use App\Http\Controllers\Api\UserController;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {

    Route::get('/reset-password/{token}', function ($token) {
        return response()->json([
            'token' => $token,
        ]);
    })->name('password.reset');

    // Authentication routes

    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::post('/logout', LogoutController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);

    Route::middleware('auth:sanctum')->group(function () {

       // Resource routes 

        Route::apiResource('user', UserController::class);
        Route::apiResource('grade', GradeController::class);
        Route::apiResource('section', SectionController::class);
        Route::apiResource('student-profile', StudentProfileController::class);
        Route::apiResource('teacher-profile', TeacherProfileController::class);

        // Supervisor routes

        Route::middleware('role:supervisor')->group(function () {

            Route::controller(EnrollmentController::class)->group(function () {
                Route::post('/enrollments', 'store');
            });

            Route::controller(TeacherSubjectController::class)->group(function () {
                Route::post('/teacher-subjects', 'store');
            });

            Route::controller(ScheduleController::class)->group(function () {
                Route::post('/schedules', 'store');
                Route::put('/schedules/{schedule}', 'update');
                Route::delete('/schedules/{schedule}', 'destroy');

                Route::get(
                    '/supervisor-schedules',
                    'supervisorSchedule'
                );
            });

            Route::controller(AttendanceController::class)->group(function () {
                Route::post('/attendances', 'store');
                Route::put('/attendances/{attendance}', 'update');
                Route::delete('/attendances/{attendance}', 'destroy');

                Route::get(
                    '/supervisor-attendances',
                    'supervisorAttendances'
                );
            });

            Route::patch(
                'teacher-absences/{teacherAbsence}/status',
                [TeacherAbsenceController::class, 'updateStatus']
            );

        });

        // Teacher routes

        Route::middleware('role:teacher')->group(function () {

            Route::controller(MarkController::class)->group(function () {
                Route::post('/marks', 'store');
                Route::get('/marks/{mark}', 'show');
                Route::put('/marks/{mark}', 'update');
                Route::delete('/marks/{mark}', 'destroy');

                Route::get('/teacher-marks', 'teacherMarks');
            });

            Route::controller(ScheduleController::class)->group(function () {
                Route::get('/teacher-schedules', 'teacherSchedule');
            });

            Route::middleware('auth:sanctum')->group(function () {

                Route::apiResource('teacher-absences', TeacherAbsenceController::class);

            });
        });

        // Student routes

        Route::middleware('role:student')->group(function () {

            Route::controller(ScheduleController::class)->group(function () {
                Route::get('/student-schedules', 'studentSchedule');
            });

            Route::controller(MarkController::class)->group(function () {
                Route::get('/student-marks', 'studentMarks');
            });

            Route::controller(AttendanceController::class)->group(function () {
                Route::get('/student-attendances', 'studentAttendances');
            });
        });
    });
});