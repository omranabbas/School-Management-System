<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Subject;
use App\Models\Section;
use App\Models\AcademicYear;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'teacher_id' => [
                'required',
                Rule::exists(User::class, 'id'),
            ],

            'subject_id' => [
                'required',
                Rule::exists(Subject::class, 'id'),
            ],

            'section_id' => [
                'required',
                Rule::exists(Section::class, 'id'),
            ],

            'academic_year_id' => [
                'required',
                Rule::exists(AcademicYear::class, 'id'),
            ],
        ];
    }
}