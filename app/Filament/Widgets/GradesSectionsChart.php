<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use Filament\Widgets\ChartWidget;

class GradesSectionsChart extends ChartWidget
{
    protected  ?string $heading = 'Sections by Grade';
    

    protected function getData(): array
    {
        $grades = Grade::withCount('sections')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Sections',
                    'data' => $grades->pluck('sections_count')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.75)',
                    'borderColor' => 'rgb(37, 99, 235)',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],

            'labels' => $grades->pluck('name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
