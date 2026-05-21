<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'score' => $this->score,

            'max_score' => $this->max_score,

            'term' => $this->term,

            'type' => $this->type,

            'exam_date' => $this->exam_date,

            'subject' => [
                'id' => $this->teacherSubject?->subject?->id,
                'name' => $this->teacherSubject?->subject?->name,
            ],

            'teacher' => [
                'id' => $this->teacherSubject?->teacher?->id,
                'name' => $this->teacherSubject?->teacher?->name,
            ],

            'student' => [
                'id' => $this->enrollment?->student?->id,
                'name' => $this->enrollment?->student?->name,
            ],

        ];
    }
}