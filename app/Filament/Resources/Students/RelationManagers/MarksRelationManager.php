<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Models\Mark;
use App\Models\StudentEnrollment;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class MarksRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $modelLabel = 'marks';
    public function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->marks
            
            ;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([

        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                ->label('Exam Type')
                ->badge()
                ->color(fn (string $state) => match ($state) {
                    'midterm' => 'warning',
                    'final' => 'success',
                })
                ->sortable(),

            TextColumn::make('score')
                ->label('Score')
                ->numeric()
                ->sortable(),

            TextColumn::make('max_score')
                ->label('Max Score')
                ->numeric()
                ->sortable(),

            TextColumn::make('term')
                ->label('Term')
                ->sortable(),

            TextColumn::make('exam_date')
                ->label('Exam Date')
                ->date()
                ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}