<?php

namespace App\Filament\Resources\AcademicYears\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                Section::make('info')->schema([
                    TextInput::make('name')
                        ->label('Year')
                        ->numeric()
                        ->minValue(2000)
                        ->maxValue(2100)
                        ->required()
                        ->unique(),
                    Toggle::make('is_active')
                        ->default(false),
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
