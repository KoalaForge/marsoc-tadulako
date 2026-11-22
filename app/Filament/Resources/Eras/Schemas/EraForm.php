<?php

declare(strict_types=1);

namespace App\Filament\Resources\Eras\Schemas;

use App\Filament\Shared\Schemas\LocaleTabs;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->helperText('Teks biasa, bukan terjemahan — nama era adalah istilah teknis/brand yang sama di ID & EN.'),
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
                    TextInput::make("uniform.{$locale}")
                        ->label("Uniform ({$locale})")
                        ->required($locale === 'id'),
                    TextInput::make("gear.{$locale}")
                        ->label("Gear ({$locale})")
                        ->required($locale === 'id'),
                    TextInput::make("helmet.{$locale}")
                        ->label("Helmet ({$locale})")
                        ->required($locale === 'id'),
                    TextInput::make("source_note.{$locale}")
                        ->label("Catatan Sumber ({$locale})")
                        ->helperText('Mis. peringatan soal foto referensi yang kurang jelas warnanya.'),
                ], label: 'Konten'),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
