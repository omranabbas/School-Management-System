<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherAbsenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'absence_date' => [
                'sometimes',
                'date',
                'after_or_equal:today',
            ],

            'reason' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
            ],

            'replacement_teacher_id' => [
                'sometimes',
                'nullable',
                Rule::exists('users', 'id'),
            ],

            'status' => [
                'sometimes',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                ]),
            ],
        ];
    }
}