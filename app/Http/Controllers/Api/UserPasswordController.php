<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPasswordRequest;

class UserPasswordController extends Controller
{
    use ApiResponse;

    public function update(
        UpdateUserPasswordRequest $request,
        User $user
    )
    {
        $this->authorize('updatePassword', $user);

        $user->update([
            'password' => $request->password,
        ]);

        return $this->successResponse(
            null,
            'Password updated successfully.'
        );
    }
}