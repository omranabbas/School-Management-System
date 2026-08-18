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
use Filament\Tables\Filters\SelectFilter;
use App\Models\Section;

class MarksRelationManager extends RelationManager
{
    protected static string $relationship = 'marks';

    protected static ?string $title = 'Student Marks';
    public function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->marks;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Exam Type')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
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
                TextColumn::make('teacherSubject.subject.name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable()->toggleable(),

                TextColumn::make('teacherSubject.teacher.name')
                    ->label('Teacher')
                    ->state(function ($record) {
                        $teacher = $record->teacherSubject?->teacher;

                        if (! $teacher) {
                            return '-';
                        }

                        return "{$teacher->name} {$teacher->father_name} {$teacher->last_name}";
                    })
                    ->searchable()
                    ->sortable()->toggleable(),
                TextColumn::make('exam_date')
                    ->label('Exam Date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])->filters([

                SelectFilter::make('academic_year_id')
                    ->label('Academic Year')
                    ->options(
                        \App\Models\AcademicYear::query()
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $academicYearId) =>
                            $query->whereHas(
                                'enrollment',
                                fn(Builder $query) =>
                                $query->where(
                                    'academic_year_id',
                                    $academicYearId
                                )
                            )
                        );
                    }),
                SelectFilter::make('term')
                    ->label('Term')
                    ->options([
                        '1' => 'First Term',
                        '2' => 'Second Term',
                    ]),

                SelectFilter::make('type')
                    ->label('Exam Type')
                    ->options([
                        'midterm' => 'Midterm',
                        'final' => 'Final',
                    ]),
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
