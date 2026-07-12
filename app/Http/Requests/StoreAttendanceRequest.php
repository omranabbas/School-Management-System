<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'enrollment_id' => [
                'required',
                Rule::exists(
                    'student_enrollments',
                    'id'
                ),
            ],

            'date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                Rule::in([
                    'present',
                    'absent',
                    'late',
                    'excused',
                ]),
            ],

        ];
    }
}