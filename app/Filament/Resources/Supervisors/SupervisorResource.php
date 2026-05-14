<?php

namespace App\Filament\Resources\Supervisors;

use App\Filament\Resources\Supervisors\Pages\CreateSupervisor;
use App\Filament\Resources\Supervisors\Pages\EditSupervisor;
use App\Filament\Resources\Supervisors\Pages\ListSupervisors;
use App\Filament\Resources\Supervisors\Pages\ViewSupervisor;
use App\Filament\Resources\Supervisors\RelationManagers\GradesRelationManager;
use App\Filament\Resources\Supervisors\Schemas\SupervisorForm;
use App\Filament\Resources\Supervisors\Schemas\SupervisorInfolist;
use App\Filament\Resources\Supervisors\Tables\SupervisorsTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SupervisorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Supervisors';

    protected static string | UnitEnum | null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'supervisor');
    }

    public static function form(Schema $schema): Schema
    {
        return SupervisorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupervisorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupervisorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            GradesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupervisors::route('/'),
            'create' => CreateSupervisor::route('/create'),
            'view' => ViewSupervisor::route('/{record}'),
            'edit' => EditSupervisor::route('/{record}/edit'),
        ];
    }
}
