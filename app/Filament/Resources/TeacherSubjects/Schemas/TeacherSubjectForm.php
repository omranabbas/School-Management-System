<?php

namespace App\Filament\Resources\TeacherSubjects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Section;
use App\Models\User;
use Filament\Schemas\Components\Section as ComponentsSection;
use Illuminate\Database\Eloquent\Builder;

class TeacherSubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('info')->schema([
                    Select::make('teacher_id')
                        ->label('Teacher')
                        ->relationship(
                            name: 'teacher',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->where('role', 'teacher')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn(User $record) =>
                            $record->name . ' ' . $record->father_name . ' ' . $record->last_name
                        )
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('section_id')
                        ->label('Section')
                        ->relationship('section', 'name')
                        ->getOptionLabelFromRecordUsing(
                            fn(Section $record) =>
                            $record->grade->name . ' - ' . $record->name
                        )
                        ->searchable()
                        ->preload()
                        ->required()->live(),

                    Select::make('subject_id')
                        ->label('Subject')
                        ->relationship(
                            name: 'subject',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query, callable $get) {

                                $sectionId = $get('section_id');

                                if (!$sectionId) {
                                    $query->whereRaw('1 = 0');
                                    return;
                                }

                                $section = Section::find($sectionId);

                                if (!$section) {
                                    $query->whereRaw('1 = 0');
                                    return;
                                }

                                $query->where('grade_id', $section->grade_id);
                            }
                        )
                        ->searchable()
                        ->preload()
                        ->required(),



                    Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->relationship('academicYear', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
