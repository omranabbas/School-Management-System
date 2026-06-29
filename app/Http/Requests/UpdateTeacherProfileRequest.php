<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'years_experience' => [
                'sometimes',
                'integer',
                'min:0',
            ],

            'certificate_image' => [
                'sometimes',
                'image',
            ],

        ];
    }
}