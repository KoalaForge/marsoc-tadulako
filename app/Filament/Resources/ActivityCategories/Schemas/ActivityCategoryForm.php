<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityCategories\Schemas;

use App\Filament\Shared\Schemas\LocaleTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ActivityCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Kunci stabil untuk filter chip — jangan diubah setelah dipakai.'),
                TextInput::make('sort')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("name.{$locale}")
                        ->label("Nama ({$locale})")
                        ->required($locale === 'id'),
                ]),
                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
