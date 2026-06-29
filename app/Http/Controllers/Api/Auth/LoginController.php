<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiResponse;

    public function __invoke(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {

            return $this->errorResponse(
                'Invalid credentials',
                401
            );

        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken(
            'auth-token'
        )->plainTextToken;

        return $this->successResponse(
            [
                'token' => $token,
                'user' => $user,
            ],
            'Login successful'
        );
    }
}