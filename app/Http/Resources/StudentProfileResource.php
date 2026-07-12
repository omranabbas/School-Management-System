<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'phone' => $this->phone,

            'parent_phone' => $this->parent_phone,

            'personal_image_url' => $this->personal_image_url,

            'student' => [
                'id' => $this->student?->id,
                'name' => $this->student?->name,
                'last_name' => $this->student?->last_name,
                'father_name' => $this->student?->father_name,
                'email' => $this->student?->email,
                'date_of_birth' => $this->student?->date_of_birth,
            ],

        ];
    }
}