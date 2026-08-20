<?php

namespace App\Filament\Resources\TeacherSubjects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;
use App\Models\Grade;

class TeacherSubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name')
                    ->label('Teacher')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->teacher->name . ' '
                            . $record->teacher->father_name . ' '
                            . $record->teacher->last_name;
                    })
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.grade.name')
                    ->label('Grade')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->icon(Heroicon::Calendar)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload(),


                SelectFilter::make('grade_id')
                    ->label('Grade')
                    ->options(
                        Grade::pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'],
                            fn($query, $gradeId) =>
                            $query->whereHas(
                                'section',
                                fn($query) =>
                                $query->where('grade_id', $gradeId)
                            )
                        );
                    }),

                SelectFilter::make('section_id')
                    ->label('Section')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),

            ])
            ->recordActions([

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
