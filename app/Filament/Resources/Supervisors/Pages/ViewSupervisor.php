<?php

namespace App\Filament\Resources\Supervisors\Pages;

use App\Filament\Resources\Supervisors\SupervisorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupervisor extends ViewRecord
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
