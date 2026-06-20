<?php

namespace App\Filament\Resources\Sections\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([

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

                TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),

                TextColumn::make('academicYear.name')
                    ->label('Academic Year'),

                TextColumn::make('created_at')
                    ->date()
                    ->icon(Heroicon::Calendar)
                    
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
