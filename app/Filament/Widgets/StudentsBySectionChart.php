<?php

namespace App\Filament\Widgets;

use App\Models\StudentEnrollment;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\AcademicYear;

class StudentsBySectionChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected  ?string $heading = 'Students by Section';

    protected int | string | array $columnSpan = 'full';

    protected  ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $academicYearId = $this->filters['academic_year_id']
            ?? AcademicYear::where('is_active', true)->value('id');

        if (!$academicYearId) {
            return [
                'datasets' => [
                    [
                        'label' => 'Students',
                        'data' => [],
                        'backgroundColor' => 'rgba(59, 130, 246, 0.75)',
                        'borderColor' => 'rgb(37, 99, 235)',
                        'borderWidth' => 1,
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => [],
            ];
        }

        $sections = StudentEnrollment::query()
            ->when(
                $academicYearId,
                fn($query) => $query->where(
                    'academic_year_id',
                    $academicYearId
                )
            )
            ->with('section.grade')
            ->selectRaw('section_id, COUNT(*) as students_count')
            ->groupBy('section_id')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $sections
                        ->pluck('students_count')
                        ->toArray(),

                    'backgroundColor' => 'rgba(59, 130, 246, 0.75)',
                    'borderColor' => 'rgb(37, 99, 235)',
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],

            'labels' => $sections
                ->map(
                    fn($item) =>
                    $item->section->grade->name . ' - ' . $item->section->name
                )
                ->toArray(),
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
