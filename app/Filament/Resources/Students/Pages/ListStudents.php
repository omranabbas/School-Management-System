<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Students\Widgets\StatsOverview;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

       protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
