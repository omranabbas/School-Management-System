<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Grade;
use App\Models\Section;
use Filament\Support\Icons\Heroicon;

class GradesSectionsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Grades', Grade::count())->description('Grades count')
                ->descriptionIcon('heroicon-o-building-library')
                ->color('success')->chart([0,0]),
            Stat::make('Sections', Section::count())->description('Sections count')
                ->descriptionIcon('heroicon-o-rectangle-group')
                ->color('success')->chart([0,0]),
        ];
    }
}
