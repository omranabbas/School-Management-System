<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')
                    ->where(fn ($query) => $query->where('grade_id', $this->grade_id))
                    ->ignore($this->subject),
            ],

            'grade_id' => [
                'required',
                Rule::exists('grades', 'id'),
            ],

        ];
    }
}