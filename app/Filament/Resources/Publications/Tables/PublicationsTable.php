<?php

declare(strict_types=1);

namespace App\Filament\Resources\Publications\Tables;

use App\Models\PublicationCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('')
                    ->collection('cover')
                    ->conversion('thumb'),
                TextColumn::make('title')
                    ->label('Judul')
                    ->getStateUsing(fn ($record): string => $record->getTranslation('title', app()->getLocale())),
                TextColumn::make('category.slug')
                    ->label('Kategori'),
                TextColumn::make('author_callsign')
                    ->label('Penulis'),
                TextColumn::make('published_on')
                    ->label('Terbit')
                    ->date('d M Y')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Dipublikasikan')
                    ->boolean(),
                IconColumn::make('is_synthetic')
                    ->label('Contoh')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->tooltip('Data contoh / placeholder')
                    ->visible(fn ($record): bool => (bool) $record?->is_synthetic),
            ])
            ->filters([
                SelectFilter::make('publication_category_id')
                    ->label('Kategori')
                    ->options(fn (): array => PublicationCategory::query()->orderBy('sort')
                        ->get()
                        ->mapWithKeys(fn (PublicationCategory $c): array => [
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
