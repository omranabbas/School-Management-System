<?php

namespace App\Filament\Resources\Teachers;

use App\Filament\Resources\Grades\RelationManagers\SubjectsRelationManager;
use App\Filament\Resources\Teachers\Pages\CreateTeachers;
use App\Filament\Resources\Teachers\Pages\EditTeachers;
use App\Filament\Resources\Teachers\Pages\ListTeachers;
use App\Filament\Resources\Teachers\Pages\ViewTeachers;
use App\Filament\Resources\Teachers\RelationManagers\TeacherSubjectsRelationManager;
use App\Filament\Resources\Teachers\Schemas\TeachersForm;
use App\Filament\Resources\Teachers\Schemas\TeachersInfolist;
use App\Filament\Resources\Teachers\Tables\TeachersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TeachersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Teachers';

    protected static string | UnitEnum | null $navigationGroup = 'Users & Staff';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'teacher');
    }
    public static function form(Schema $schema): Schema
    {
        return TeachersForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeachersInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeachersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
    TeacherSubjectsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeachers::route('/'),
            'create' => CreateTeachers::route('/create'),
            'view' => ViewTeachers::route('/{record}'),
            'edit' => EditTeachers::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
