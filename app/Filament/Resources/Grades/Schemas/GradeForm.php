<?php

namespace App\Filament\Resources\Grades\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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

                    // Select::make('stage')
                    //     ->label('Stage')
                    //     ->options([
                    //         'primary' => 'Primary',
                    //         'preparatory' => 'Preparatory',
                    //         'secondary' => 'Secondary',
                    //     ])
                    //     ->required(),

                    Select::make('supervisor_id')
                        ->label('Supervisor')
                        ->relationship(
                            name: 'supervisor',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->where('role', 'supervisor')
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
