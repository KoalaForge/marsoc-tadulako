<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaItems\Tables;

use App\Enums\MediaCategory;
use App\Enums\MediaType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MediaItemsTable
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
                TextColumn::make('title')
                    ->label('Judul')
                    ->getStateUsing(fn ($record): string => $record->getTranslation('title', app()->getLocale())),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (MediaType $state): string => match ($state) {
                        MediaType::Photo => 'success',
                        MediaType::Demo => 'warning',
                        MediaType::Placeholder => 'gray',
                    }),
                TextColumn::make('year_label')
                    ->label('Tahun'),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
                IconColumn::make('is_synthetic')
                    ->label('Contoh')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->tooltip('Data contoh / placeholder')
                    ->visible(fn ($record): bool => (bool) $record?->is_synthetic),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(MediaCategory::class),
                SelectFilter::make('type')
                    ->label('Jenis')
                    ->options(MediaType::class),
                TernaryFilter::make('is_synthetic')
                    ->label('Data')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya contoh')
                    ->falseLabel('Hanya asli'),
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
