<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'day' => $this->day,

            'period' => $this->period,

            'start_time' => $this->start_time,

            'end_time' => $this->end_time,

            'status' => $this->override_status ?? 'scheduled',

            'subject' => [
                'id' => $this->teacherSubject?->subject?->id,
                'name' => $this->teacherSubject?->subject?->name,
            ],

            'teacher' => [
                'id' => $this->display_teacher?->id
                    ?? $this->teacherSubject?->teacher?->id,

                'name' => $this->display_teacher?->name
                    ?? $this->teacherSubject?->teacher?->name,
            ],

            'replacement_teacher' => $this->replacement_teacher
                ? [
                    'id' => $this->replacement_teacher->id,
                    'name' => $this->replacement_teacher->name,
                ]
                : null,

            'section' => [
                'id' => $this->teacherSubject?->section?->id,
                'name' => $this->teacherSubject?->section?->name,
            ],

        ];
    }
}