<?php

namespace App\Filament\Resources\Supervisors\Tables;


use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Icons\Heroicon;

class SupervisorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('father_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->icon(Heroicon::Calendar)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('supervisorProfile.stage')
                    ->label('stage')
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'primary' => 'danger',
                            'preparatory' => 'info',
                            'secondary' => 'success'
                        };
                    }),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
