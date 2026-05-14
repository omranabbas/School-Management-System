<?php

namespace App\Filament\Resources\Students\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
                 Stat::make('Students', User::where('role', 'student')->count())
                ->description('Students count')
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->chart(collect(range(6, 0))->map(function ($i) {
                    return User::where('role', 'student')
                        ->whereDate('created_at', now()->subDays($i))
                        ->count();
                })->toArray()),
        ];
    }
}
