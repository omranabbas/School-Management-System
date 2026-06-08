<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return $this->successResponse(
            null,
            'Logged out successfully'
        );
    }
}