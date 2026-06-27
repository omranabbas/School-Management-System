<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
use App\Models\StudentEnrollment;
use App\Models\TeacherSubject;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class AcademicYearStats extends StatsOverviewWidget
{



    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $academicYearId = $this->filters['academic_year_id'] ?? null;
        return [
            Stat::make(
                'Students',
                StudentEnrollment::where(
                    'academic_year_id',
                    $academicYearId
                )->count()
            )->description('Students count '/* in '.AcademicYear::find($academicYearId)->name */)
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make(
                'Teachers',
                TeacherSubject::where(
                    'academic_year_id',
                    $academicYearId
                )
                    ->distinct('teacher_id')
                    ->count('teacher_id')
            )->description('Teachers count '/*in '.AcademicYear::find($academicYearId)->name*/)
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
        ];
    }
}
