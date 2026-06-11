<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'date' => $this->date,

            'status' => $this->status,

            'student' => [
                'id' => $this->enrollment?->student?->id,
                'name' => $this->enrollment?->student?->name,
            ],

            'section' => [
                'id' => $this->enrollment?->section?->id,
                'name' => $this->enrollment?->section?->name,
            ],

        ];
    }
}