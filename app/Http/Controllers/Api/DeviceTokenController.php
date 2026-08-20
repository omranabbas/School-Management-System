<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceTokenRequest;
use App\Models\UserDevice;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    use ApiResponse;

    public function store(StoreDeviceTokenRequest $request)
    {
        $device = $request->user()
            ->devices()
            ->updateOrCreate(
                [
                    'device_token' => $request->device_token,
                ],
                [
                    'device_type' => $request->device_type,
                ]
            );

        return $this->successResponse(
            $device,
            'Device token registered successfully',
            201
        );
    }

    public function destroy(Request $request, UserDevice $deviceToken)
    {
        abort_unless(
            $deviceToken->user_id === $request->user()->id,
            404
        );

        $deviceToken->delete();

        return $this->successResponse(
            null,
            'Device token removed successfully'
        );
    }
}