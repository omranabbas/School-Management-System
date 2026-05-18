<?php

namespace App\Filament\Resources\Grades\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\User;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Grade info')->schema([
                    TextInput::make('name')
                        ->label('Grade Name')
                        ->required()
                        ->maxLength(255),

                    Select::make('supervisor_id')
                        ->label('Supervisor')
                        ->relationship(
                            name: 'supervisor',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->where('role', 'supervisor')
                        )
                        ->getOptionLabelFromRecordUsing(
                            fn(User $record) =>
                            $record->name . ' ' . $record->father_name . ' ' . $record->last_name
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
