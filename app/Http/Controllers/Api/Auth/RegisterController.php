<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'last_name' => ['required'],
            'father_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'not_regex:/\s/',
            ],
            'date_of_birth' => ['required', 'date'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'father_name' => $data['father_name'],
            'email' => $data['email'],
            'role' => 'admin',
            'password' => Hash::make($data['password']),
            'date_of_birth' => $data['date_of_birth'],
        ]);

        // $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin registered successfully',
            // 'token' => $token,
            'user' => $user,
        ], 201);
    }
}
