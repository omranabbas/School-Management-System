<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
use App\Models\StudentEnrollment;
use App\Models\TeacherSubject;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class AcademicYearStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $academicYearId = $this->filters['academic_year_id']
            ?? AcademicYear::where('is_active', true)->value('id');

        $academicYear = $academicYearId
            ? AcademicYear::find($academicYearId)
            : null;

        if ($academicYear) {
            $studentCount = StudentEnrollment::where(
                'academic_year_id',
                $academicYearId
            )->count();

            $teacherCount = TeacherSubject::where(
                'academic_year_id',
                $academicYearId
            )
                ->distinct('teacher_id')
                ->count('teacher_id');
        } else {
            $studentCount = StudentEnrollment::count();

            $teacherCount = TeacherSubject::distinct('teacher_id')
                ->count('teacher_id');
        }

        return [
            Stat::make('Students', $studentCount)
                ->description(
                    $academicYear
                        ? 'Students in ' . $academicYear->name
                        : 'All students'
                )
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')->chart([0,0]),

            Stat::make('Teachers', $teacherCount)
                ->description(
                    $academicYear
                        ? 'Teachers in ' . $academicYear->name
                        : 'All teachers'
                )
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info')->chart([0,0]),
        ];
    }
}