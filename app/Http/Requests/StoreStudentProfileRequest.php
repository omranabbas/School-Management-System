<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'student_id' => [
                'required',
                Rule::exists('users', 'id'),
                'unique:student_profiles,student_id',
            ],

            'phone' => [
                'required',
                'string',
            ],

            'parent_phone' => [
                'required',
                'string',
            ],

            'personal_image' => [
                'required',
                'image',
            ],

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $student = User::find($this->student_id);

            if (
                $student &&
                $student->role !== 'student'
            ) {

                $validator->errors()->add(
                    'student_id',
                    'Selected user is not a student.'
                );

            }

        });
    }
}