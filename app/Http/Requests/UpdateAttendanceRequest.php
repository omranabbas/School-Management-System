<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => [
                'sometimes',
                'exists:student_enrollments,id',
            ],

            'date' => [
                'sometimes',
                'date',
            ],

            'status' => [
                'sometimes',
                'in:present,absent,late,excused',
            ],
        ];
    }
}