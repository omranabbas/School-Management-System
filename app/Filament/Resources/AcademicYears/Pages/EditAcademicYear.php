<?php

namespace App\Filament\Resources\AcademicYears\Pages;

use App\Filament\Resources\AcademicYears\AcademicYearResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;


class EditAcademicYear extends EditRecord
{
    protected static string $resource = AcademicYearResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['is_active']) {
            \App\Models\AcademicYear::where('id', '!=', $this->record->id)
                ->update(['is_active' => false]);
        }

        return $data;
    }


    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
