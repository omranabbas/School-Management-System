<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'years_experience' => $this->years_experience,

            'certificate_image_url' => $this->certificate_image_url,

            'teacher' => [
                'id' => $this->teacher?->id,
                'name' => $this->teacher?->name,
                'last_name' => $this->teacher?->last_name,
                'father_name' => $this->teacher?->father_name,
                'email' => $this->teacher?->email,
                'date_of_birth' => $this->teacher?->date_of_birth,
            ],

        ];
    }
}