<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;

class AttendanceController extends Controller
{
    public function store(StoreAttendanceRequest $request)
    {
        $attendance = Attendance::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'data' => $attendance,
        ], 201);
    }
}