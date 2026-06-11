<?php

namespace App\Http\Requests;

use App\Models\Schedule;
use App\Models\TeacherSubject;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'teacher_subject_id' => [
                'required',
                Rule::exists(
                    'teacher_subjects',
                    'id'
                ),
            ],

            'day' => [
                'required',
                Rule::in([
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                ]),
            ],

            'period' => [
                'required',
                'integer',
                'min:1',
            ],

            'start_time' => [
                'required',
                'date_format:H:i',
            ],

            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $teacherSubject = TeacherSubject::with([
                'section.grade'
            ])->find($this->teacher_subject_id);

            if (! $teacherSubject) {
                return;
            }
            
            $user = Auth::user();

            if (
                $user &&
                $user->role === 'supervisor' &&
                $teacherSubject->section->grade->supervisor_id !== $user->id
            ) {

                $validator->errors()->add(
                    'teacher_subject_id',
                    'You cannot manage schedules outside your assigned grades.'
                );

                return;
            }

            $teacherConflict = Schedule::where(
                'day',
                $this->day
            )
                ->where(
                    'period',
                    $this->period
                )
                ->whereHas(
                    'teacherSubject',
                    function ($query) use ($teacherSubject) {

                        $query->where(
                            'teacher_id',
                            $teacherSubject->teacher_id
                        );

                    }
                )
                ->exists();

            if ($teacherConflict) {

                $validator->errors()->add(
                    'teacher_subject_id',
                    'Teacher already has a class in this period.'
                );

            }

            $sectionConflict = Schedule::where(
                'day',
                $this->day
            )
                ->where(
                    'period',
                    $this->period
                )
                ->whereHas(
                    'teacherSubject',
                    function ($query) use ($teacherSubject) {

                        $query->where(
                            'section_id',
                            $teacherSubject->section_id
                        );

                    }
                )
                ->exists();

            if ($sectionConflict) {

                $validator->errors()->add(
                    'period',
                    'Section already has a class in this period.'
                );

            }
        });
    }
}