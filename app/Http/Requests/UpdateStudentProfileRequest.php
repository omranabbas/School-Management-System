<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'phone' => [
                'sometimes',
                'string',
            ],

            'parent_phone' => [
                'sometimes',
                'string',
            ],

            'personal_image' => [
                'sometimes',
                'image',
            ],

        ];
    }
}