<?php

declare(strict_types=1);

namespace App\Filament\Resources\WeaponVariants\Schemas;

use App\Enums\WeaponCategory;
use App\Filament\Shared\Schemas\LocaleTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WeaponVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Key')
                    ->required(fn (string $operation): bool => $operation === 'edit')
                    ->unique(ignoreRecord: true)
                    ->maxLength(80)
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Str::slug($state) : null)
                    ->helperText('Kosongkan untuk dibuat otomatis dari nama, mis. mk18-urgi.'),
                Select::make('category')
                    ->label('Kategori')
                    ->options(WeaponCategory::class)
                    ->required()
                    ->native(false),
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->helperText('Teks biasa, bukan terjemahan — nama/designasi senjata adalah istilah teknis.'),
                TextInput::make('sort')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                SpatieMediaLibraryFileUpload::make('photos')
                    ->label('Foto')
                    ->collection('photos')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->imageEditor(),

                LocaleTabs::make(fn (string $locale): array => [
                    Textarea::make("description.{$locale}")
                        ->label("Deskripsi ({$locale})")
                        ->rows(2),
                ], label: 'Konten'),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
