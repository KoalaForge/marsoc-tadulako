<?php

declare(strict_types=1);

namespace App\Filament\Resources\Personnels\Tables;

use App\Enums\PersonnelStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PersonnelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                SpatieMediaLibraryImageColumn::make('portrait')
                    ->label('')
                    ->collection('portrait')
                    ->conversion('thumb')
                    ->circular(),
                TextColumn::make('callsign')
                    ->label('Callsign')
                    ->sortable(),
                TextColumn::make('member_id')
                    ->label('Member ID'),
                TextColumn::make('role')
                    ->label('Peran')
                    ->getStateUsing(fn ($record): string => $record->getTranslation('role', app()->getLocale())),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('era.key')
                    ->label('Era'),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
                IconColumn::make('is_synthetic')
                    ->label('Contoh')
                    ->icon('heroicon-o-beaker')
                    ->color('warning')
                    ->tooltip('Data contoh / placeholder')
                    ->visible(fn ($record): bool => (bool) $record?->is_synthetic),
                TextColumn::make('sort')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(PersonnelStatus::class),
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
