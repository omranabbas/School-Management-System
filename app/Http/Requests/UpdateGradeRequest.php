<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $grade = $this->route('grade');

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('grades', 'name')
                    ->ignore($grade->id),
            ],
        ];
    }
}