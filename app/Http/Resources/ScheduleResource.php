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

            'subject' => [
                'id' => $this->teacherSubject?->subject?->id,
                'name' => $this->teacherSubject?->subject?->name,
            ],

            'teacher' => [
                'id' => $this->teacherSubject?->teacher?->id,
                'name' => $this->teacherSubject?->teacher?->name,
            ],

            'section' => [
                'id' => $this->teacherSubject?->section?->id,
                'name' => $this->teacherSubject?->section?->name,
            ],

        ];
    }
}