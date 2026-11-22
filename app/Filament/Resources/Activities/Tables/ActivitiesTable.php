<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Tables;

use App\Models\ActivityCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('occurred_on')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->getStateUsing(fn ($record): string => $record->getTranslation('title', app()->getLocale())),
                TextColumn::make('category.slug')
                    ->label('Kategori'),
                TextColumn::make('location')
                    ->label('Lokasi'),
                TextColumn::make('participants')
                    ->label('Peserta')
                    ->sortable(),
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
                SelectFilter::make('activity_category_id')
                    ->label('Kategori')
                    ->options(fn (): array => ActivityCategory::query()->orderBy('sort')
                        ->get()
                        ->mapWithKeys(fn (ActivityCategory $c): array => [
                            $c->getKey() => $c->getTranslation('name', app()->getLocale()),
                        ])
                        ->all()),
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
