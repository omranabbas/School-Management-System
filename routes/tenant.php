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

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {

    // Auth

    Route::post('/register', RegisterController::class);

    Route::post('/login', LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {


        Route::middleware('role:admin')->group(function () {

            // Teacher Assignments

            Route::post(
                '/teacher-subjects',
                [TeacherSubjectController::class, 'store']
            );

        });

        Route::middleware('role:admin,supervisor')->group(function () {

            // Student Enrollment

            Route::post(
                '/enrollments',
                [EnrollmentController::class, 'store']
            );

        });

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

        });

    });

});