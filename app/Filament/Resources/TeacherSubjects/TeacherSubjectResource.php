<?php

namespace App\Filament\Resources\TeacherSubjects;

use App\Filament\Resources\TeacherSubjects\Pages\CreateTeacherSubject;
use App\Filament\Resources\TeacherSubjects\Pages\EditTeacherSubject;
use App\Filament\Resources\TeacherSubjects\Pages\ListTeacherSubjects;
use App\Filament\Resources\TeacherSubjects\Schemas\TeacherSubjectForm;
use App\Filament\Resources\TeacherSubjects\Tables\TeacherSubjectsTable;
use App\Models\TeacherSubject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TeacherSubjectResource extends Resource
{
    protected static ?string $model = TeacherSubject::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

        protected static string | UnitEnum | null $navigationGroup = 'Academic Operations';

    public static function form(Schema $schema): Schema
    {
        return TeacherSubjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherSubjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeacherSubjects::route('/'),
            'create' => CreateTeacherSubject::route('/create'),
            'edit' => EditTeacherSubject::route('/{record}/edit'),
        ];
    }
}
