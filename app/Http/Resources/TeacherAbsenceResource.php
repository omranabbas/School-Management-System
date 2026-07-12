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
            ],

            'absence_date' => $this->absence_date,

            'reason' => $this->reason,

            'replacement_teacher' => $this->replacementTeacher
                ? [
                    'id' => $this->replacementTeacher->id,
                    'name' => $this->replacementTeacher->name,
                ]
                : null,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}