<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [

            'name' => [
                'sometimes',
                'string',
            ],

            'last_name' => [
                'sometimes',
                'string',
            ],

            'father_name' => [
                'sometimes',
                'string',
            ],

            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')
                    ->ignore($user->id),
            ],

            'password' => [
                'sometimes',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'not_regex:/\s/',
            ],

            'date_of_birth' => [
                'sometimes',
                'date',
            ],

            'role' => [
                'sometimes',
                'in:student,teacher',
            ],
        ];
    }
}