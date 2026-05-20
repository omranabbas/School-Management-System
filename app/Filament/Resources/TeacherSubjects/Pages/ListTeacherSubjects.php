<?php

namespace App\Filament\Resources\TeacherSubjects\Pages;

use App\Filament\Resources\TeacherSubjects\TeacherSubjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeacherSubjects extends ListRecords
{
    protected static string $resource = TeacherSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
