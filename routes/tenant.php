<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;

use App\Http\Controllers\Api\AttendanceController;

use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\MarkController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StudentEnrollmentController;
use App\Http\Controllers\Api\StudentProfileController;
use App\Http\Controllers\Api\TeacherProfileController;
use App\Http\Controllers\Api\TeacherSubjectController;
use App\Http\Controllers\Api\TeacherAbsenceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\UserController;
use App\Models\AcademicYear;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {

    // Authentication routes

    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);


    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', LogoutController::class);

        Route::controller(DeviceTokenController::class)->group(function () {
            Route::post('/device-tokens', 'store');
            Route::delete('/device-tokens/{deviceToken}', 'destroy');
        });

        // Notification routes
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index');
            Route::get('/notifications/unread', 'unread');

            Route::patch('/notifications/{id}/read', 'markAsRead');
            Route::patch('/notifications/read-all', 'markAllAsRead');

            Route::delete('/notifications/{id}', 'destroy');
        });

        // Resource routes 
        Route::apiResource('user', UserController::class);
        Route::apiResource('grade', GradeController::class);
        Route::apiResource('section', SectionController::class);
        Route::apiResource('student-profile', StudentProfileController::class);
        Route::apiResource('teacher-profile', TeacherProfileController::class);
        Route::get('/academic-years', function () {
            $academicYears = AcademicYear::orderByDesc('name')->get();

            return response(
                $academicYears,
                200,
                [
                    'Academic years fetched successfully'
                ]
            );
        });
        Route::apiResource('/enrollments', StudentEnrollmentController::class);

        Route::apiResource('subjects', SubjectController::class);
        Route::apiResource('/teacher-subject', TeacherSubjectController::class);

        Route::middleware('role:supervisor')->group(function () {

            Route::controller(ScheduleController::class)->group(function () {
                Route::post('/schedules', 'store');
                Route::put('/schedules/{schedule}', 'update');
                Route::delete('/schedules/{schedule}', 'destroy');

                Route::get(
                    '/supervisor-schedules',
                    'supervisorSchedule'
                );
            });
            Route::controller(MarkController::class)->group(function () {

                Route::get('/mark/{mark}', 'show');
                Route::get('/marks/{studentId}', 'studentMarksById');
            });



            Route::controller(AttendanceController::class)->group(function () {
                Route::post('/attendances', 'store');
                Route::put('/attendances/{attendance}', 'update');
                Route::delete('/attendances/{attendance}', 'destroy');
                Route::get(
                    '/attendances/{studentId}',
                    'studentAttendancesById'
                );
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

                Route::get('/marks/{studentId}', 'studentMarksById');
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
                Route::get(
                    '/student-schedules',
                    'studentSchedule'
                );
            });

            Route::controller(MarkController::class)->group(function () {
                Route::get('/marks', 'studentMarks');
            });

            Route::controller(AttendanceController::class)->group(function () {
                Route::get('/student-attendances', 'studentAttendances');
            });
        });
    });
});
