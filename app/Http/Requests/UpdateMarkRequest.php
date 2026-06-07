<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'score' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            'max_score' => [
                'sometimes',
                'numeric',
                'min:1',
            ],

            'term' => [
                'sometimes',
                'integer',
            ],

            'type' => [
                'sometimes',
                'in:midterm,final',
            ],

            'exam_date' => [
                'sometimes',
                'date',
            ],
        ];
    }
}