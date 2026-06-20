<?php

namespace App\Filament\Resources\StudentEnrollments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;

class StudentEnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),

                TextColumn::make('section.grade.name')
                    ->label('Grade'),

                TextColumn::make('section.name')
                    ->label('Section'),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year'),

                TextColumn::make('created_at')
                    ->date()
                    ->icon(Heroicon::Calendar)
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                 SelectFilter::make('academic_year_id')
                ->label('Academic Year')
                ->relationship('academicYear', 'name')
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
