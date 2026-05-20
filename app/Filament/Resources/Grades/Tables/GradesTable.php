<?php

namespace App\Filament\Resources\Grades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;

class GradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supervisor.supervisorProfile.stage')
                    ->label('stage')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'primary' => 'success',
                        'preparatory' => 'info',
                        'secondary' => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('supervisor.name')
                    ->label('Supervisor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->icon(Heroicon::Calendar)
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('stage')
                    ->label('Stage')
                    ->options([
                        'primary' => 'Primary',
                        'preparatory' => 'Preparatory',
                        'secondary' => 'Secondary',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('supervisor.supervisorProfile', function ($q) use ($data) {
                                $q->where('stage', $data['value']);
                            });
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
