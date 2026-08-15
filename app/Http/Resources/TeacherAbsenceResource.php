<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAbsenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'teacher' => [
                'id' => $this->teacher?->id,
                'name' => $this->teacher?->name,
                'last_name' => $this->teacher?->last_name,
                'father_name' => $this->teacher?->father_name,
            ],

            'absence_date' => $this->absence_date,

            'reason' => $this->reason,

            'replacement_teacher' => $this->replacementTeacher
                ? [
                    'id' => $this->replacementTeacher->id,
                    'name' => $this->replacementTeacher->name,
                    'last_name' => $this->replacementTeacher->last_name,
                    'father_name' => $this->replacementTeacher->father_name,
                ]
                : null,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
