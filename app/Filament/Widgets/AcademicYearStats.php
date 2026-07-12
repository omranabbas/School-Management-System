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
        $academicYearId = $this->filters['academic_year_id'] ??  AcademicYear::where('is_active',true)->value('id');
          $academicYear = $academicYearId
        ? AcademicYear::find($academicYearId)
        : null;
        return [
            Stat::make(
                'Students',
                StudentEnrollment::where(
                    'academic_year_id',
                    $academicYearId
                )->count()
<<<<<<< HEAD
            )
            ->description(  $academicYear?'Students count in ' . $academicYear->name : 'Students count')
=======
            )->description('Students count '/* in '.AcademicYear::find($academicYearId)->name */)
>>>>>>> 2d175bdda271a80461a5b0ef700660d9b2c5332f
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
<<<<<<< HEAD
            )->description(  $academicYear?'Students count in ' . $academicYear->name : 'Students count')
=======
            )->description('Teachers count '/*in '.AcademicYear::find($academicYearId)->name*/)
>>>>>>> 2d175bdda271a80461a5b0ef700660d9b2c5332f
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
        ];
    }
}
