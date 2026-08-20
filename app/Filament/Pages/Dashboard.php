<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AcademicYearStats;
use App\Filament\Widgets\GradesSectionsChart;
use App\Filament\Widgets\GradesSectionsStats;
use App\Filament\Widgets\MarksAverageChart;
use App\Filament\Widgets\StudentsBySectionChart;
use App\Filament\Widgets\ThemeColorWidget;
use App\Models\AcademicYear;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getHeaderWidgets(): array
    {
        return [
            GradesSectionsStats::class,
            GradesSectionsChart::class,
            MarksAverageChart::class
        ];
    }
    public function getFooterWidgets(): array
    {
        return [
            AcademicYearStats::class,
            StudentsBySectionChart::class
            
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('academic_year_id')
                ->label('Academic year')
                ->options(
                    AcademicYear::pluck('name', 'id')
                )
                ->searchable()
                ->preload(),
        ]);
    }
}
