<?php

namespace App\Filament\Resources\Teachers\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Teachers', User::where('role', 'teacher')->count())
                ->description('Teachers count')
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->chart(collect(range(6, 0))->map(function ($i) {
                    return User::where('role', 'teacher')
                        ->whereDate('created_at', now()->subDays($i))
                        ->count();
                })->toArray()),
        ];
    }
}
