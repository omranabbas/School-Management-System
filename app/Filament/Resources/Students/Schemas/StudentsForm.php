<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;

class StudentsForm
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
                                    ->required(),
                                TextInput::make('last_name')
                                    ->required(),
                                TextInput::make('father_name')
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                DatePicker::make('date_of_birth')
                                    ->required(),
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
                            ->default('student'),
                    ]),

                Section::make('Student Profile')
                    ->relationship('studentProfile')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),

                                TextInput::make('parent_phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                                FileUpload::make('personal_image')
                                    ->directory('personal_images')
                                    ->disk('public')
                                    ->required()
                                    ->columnSpanFull()
                                    ->image()
                                    ->maxSize(2048)->acceptedFileTypes(['image/jpeg', 'image/png']),
                                // TextInput::make('personal_image')->required()

                            ]),
                    ]),
            ]);
    }
}
