<?php

namespace App\Filament\Resources\AcademicYears\Pages;

use App\Filament\Resources\AcademicYears\AcademicYearResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\AcademicYear;
use Override;

class CreateAcademicYear extends CreateRecord
{
    protected static string $resource = AcademicYearResource::class;



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['is_active']) {
            \App\Models\AcademicYear::where('name', '!=', $data['name'])
                ->update(['is_active' => false]);
        }

        return $data;
    }
}
