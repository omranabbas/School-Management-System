<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
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
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section.grade.name')
                    ->label('Grade'),

                TextColumn::make('section.name')
                    ->label('Section'),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year'),

                TextColumn::make('created_at')
                    ->dateTime()
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
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
