<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use ApiResponse;

    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'father_name' => $data['father_name'],
            'email' => $data['email'],
            'role' => 'admin',
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'],
        ]);

        return $this->successResponse(
            $user,
            'Admin registered successfully',
            201
        );
    }
}