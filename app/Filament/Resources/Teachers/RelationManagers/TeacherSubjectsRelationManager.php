<?php

namespace App\Filament\Resources\Teachers\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use App\Models\Section;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;

class TeacherSubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'teacherSubjects';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            
            ->columns([
                 TextColumn::make('subject.name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('section.grade.name')
                    ->label('Section')
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
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
