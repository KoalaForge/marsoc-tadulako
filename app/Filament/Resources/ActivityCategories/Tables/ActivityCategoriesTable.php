<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('slug')
                    ->label('Slug'),
                TextColumn::make('name')
                    ->label('Nama')
                    ->getStateUsing(fn ($record): string => $record->getTranslation('name', app()->getLocale())),
                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
