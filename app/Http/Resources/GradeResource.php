<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,
            'sections_count' => $this->sections_count,
            'students_count' => $this->students_count,
            'supervisor' => [
                'id' => $this->supervisor?->id,
                'name' => $this->supervisor?->name,
            ],

        ];
    }
}
