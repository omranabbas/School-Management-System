<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherAbsenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'absence_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:500',
            ],

            'replacement_teacher_id' => [
                'nullable',
                Rule::exists('users', 'id'),
            ],
        ];
    }
}