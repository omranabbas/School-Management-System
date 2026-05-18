<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMarkRequest extends FormRequest
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
                Rule::exists('student_enrollments', 'id'),
            ],

            'teacher_subject_id' => [
                'required',
                Rule::exists('teacher_subjects', 'id'),
            ],

            'score' => [
                'required',
                'numeric',
                'min:0',
            ],

            'max_score' => [
                'required',
                'numeric',
                'gt:score',
            ],

            'term' => [
                'required',
                'integer',
                'between:1,2',
            ],

            'type' => [
                'required',
                Rule::in(['midterm', 'final']),
            ],

            'exam_date' => [
                'required',
                'date',
            ],

        ];
    }
}