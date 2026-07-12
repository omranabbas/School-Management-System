<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherSubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'teacher' => [
                'id' => $this->teacher?->id,
                'name' => $this->teacher?->name,
            ],

            'subject' => [
                'id' => $this->subject?->id,
                'name' => $this->subject?->name,
            ],

            'section' => [
                'id' => $this->section?->id,
                'name' => $this->section?->name,
            ],

            'grade' => [
                'id' => $this->section?->grade?->id,
                'name' => $this->section?->grade?->name,
            ],

            'academic_year' => [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
            ],

        ];
    }
}