<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Grade;
use App\Models\Section;

class GradesSectionsStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Grades', Grade::count())->description('Grades count')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
            Stat::make('Sections', Section::count())->description('Sections count')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
        ];
    }
}
