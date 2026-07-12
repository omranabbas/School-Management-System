<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'teacher_id' => [
                'required',
                Rule::exists('users', 'id'),
                'unique:teacher_profiles,teacher_id',
            ],

            'years_experience' => [
                'required',
                'integer',
                'min:0',
            ],

            'certificate_image' => [
                'required',
                'image',
            ],

        ];
    }
}