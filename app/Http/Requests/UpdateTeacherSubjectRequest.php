<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Subject;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\TeacherSubject;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherSubjectRequest extends FormRequest
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

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {

    //         $exists = TeacherSubject::where([
    //             'teacher_id' => $this->teacher_id,
    //             'subject_id' => $this->subject_id,
    //             'section_id' => $this->section_id,
    //             'academic_year_id' => $this->academic_year_id,
    //         ])
    //         ->where('id', '!=', $this->teacherSubject->id)
    //         ->exists();

    //         if ($exists) {
    //             $validator->errors()->add(
    //                 'teacher_id',
    //                 'This assignment already exists.'
    //             );
    //         }

    //     });
    // }
}