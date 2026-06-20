<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class TeachersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('last_name')
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('father_name')
                                    ->maxLength(255)
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                DatePicker::make('date_of_birth')
                                    ->required()
                                    ->maxDate(now()),
                                TextInput::make('password')
                                    ->password()->minLength(8)
                                    ->rules([
                                        'regex:/[a-z]/',
                                        'regex:/[A-Z]/',
                                        'regex:/[0-9]/',
                                        'regex:/[@$!%*#?&]/',
                                        'not_regex:/\s/',
                                    ])->validationMessages([
                                        'regex' => 'Password must contain uppercase, lowercase, number and special character.',
                                        'not_regex' => 'Password must not contain spaces.',
                                    ])->required(fn(string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn($state) => filled($state))->revealable(),
                            ]),
                        Hidden::make('role')
                            ->default('teacher'),
                    ]),

                Section::make('Teacher Profile')
                    ->relationship('teacherProfile')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('years_experience')
                                    ->numeric()
                                    ->required()->minValue(0),
                            ]),
                    ]),
            ]);
    }
}
