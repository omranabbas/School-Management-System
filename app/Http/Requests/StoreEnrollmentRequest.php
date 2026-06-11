<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrollmentRequest extends FormRequest
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
            ],

            // 'grade_id' => [
            //     'required',
            //     Rule::exists('grades', 'id'),
            // ],

            'section_id' => [
                'required',
                Rule::exists('sections', 'id'),
            ],

            'academic_year_id' => [
                'required',
                Rule::exists('academic_years', 'id'),
            ],

            // 'enrollment_date' => [
            //     'required',
            //     'date',
            // ],

        ];
    }
}