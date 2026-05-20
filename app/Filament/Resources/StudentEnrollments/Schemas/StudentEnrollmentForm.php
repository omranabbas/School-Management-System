<?php

namespace App\Filament\Resources\StudentEnrollments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Schemas\Components\Section;

class StudentEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('info')->schema([
                    Select::make('student_id')
                        ->label('Student')
                        ->relationship(
                            name: 'student',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->where('role', 'student')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn(User $record) =>
                            $record->name . ' ' . $record->father_name . ' ' . $record->last_name
                        )
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('section_id')
                        ->label('Grade / Section')
                        ->relationship(
                            name: 'section',
                            titleAttribute: 'name',
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn(\App\Models\Section $record) =>
                            $record->grade->name . ' / ' . $record->name
                        )
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->relationship(
                            name: 'academicYear',
                            titleAttribute: 'name'
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
