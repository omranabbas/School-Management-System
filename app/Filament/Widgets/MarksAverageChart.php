<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
use App\Models\Mark;
use Filament\Widgets\ChartWidget;

class MarksAverageChart extends ChartWidget
{
    protected ?string $heading = 'Average Marks by Academic Year';


    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $academicYears = AcademicYear::query()
            ->withCount('enrollments')
            ->orderBy('name')
            ->get();

        $averages = Mark::query()
            ->selectRaw('
                academic_years.id as academic_year_id,
                AVG((marks.score / marks.max_score) * 100) as average
            ')
            ->join(
                'student_enrollments',
                'student_enrollments.id',
                '=',
                'marks.enrollment_id'
            )
            ->join(
                'academic_years',
                'academic_years.id',
                '=',
                'student_enrollments.academic_year_id'
            )
            ->groupBy('academic_years.id')
            ->pluck('average', 'academic_year_id');

        return [
            'datasets' => [
                [
                    'label' => 'Average Marks %',

                    'data' => $academicYears
                        ->map(
                            fn ($year) =>
                                round($averages[$year->id] ?? 0, 2)
                        )
                        ->toArray(),

                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',

                    'fill' => true,
                    'tension' => 0.3,

                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                ],
            ],

            'labels' => $academicYears
                ->pluck('name')
                ->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,

                    'ticks' => [
                        'stepSize' => 10,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}