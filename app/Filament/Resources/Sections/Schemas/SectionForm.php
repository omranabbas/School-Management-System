<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->label('Section Name')
                ->required()
                ->maxLength(255),

            Select::make('grade_id')
                ->label('Grade')
                ->relationship('grade', 'name')
                ->searchable()
                ->preload()
                ->required(),
            ]);
    }
}
