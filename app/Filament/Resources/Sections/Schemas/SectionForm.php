<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Models\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section as ComponentsSection;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Section info')->schema([
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
                ])->columnSpanFull()->columns(2)

            ]);
    }
}
