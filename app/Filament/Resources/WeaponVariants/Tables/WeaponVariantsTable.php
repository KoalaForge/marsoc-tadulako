<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeaponVariants\Tables;

use App\Enums\WeaponCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WeaponVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('photos')
                    ->label('')
                    ->collection('photos')
                    ->conversion('thumb')
                    ->limit(1),
                TextColumn::make('key')
                    ->label('Key'),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->wrap(),
                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(WeaponCategory::class),
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
