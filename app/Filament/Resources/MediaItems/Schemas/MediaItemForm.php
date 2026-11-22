<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaItems\Schemas;

use App\Enums\MediaCategory;
use App\Enums\MediaType;
use App\Filament\Shared\Schemas\LocaleTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MediaItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Record')
                    ->schema([
                        TextInput::make('key')
                            ->label('Key')
                            ->required(fn (string $operation): bool => $operation === 'edit')
                            ->unique(ignoreRecord: true)
                            ->maxLength(80)
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Str::slug($state) : null)
                            ->helperText('Kosongkan untuk dibuat otomatis dari judul (ID), mis. foto-latihan-dasar. Jadi bagian URL /media/{key}.'),
                        Select::make('category')
                            ->label('Kategori')
                            ->options(MediaCategory::class)
                            ->required()
                            ->native(false),
                        Select::make('type')
                            ->label('Jenis')
                            ->options(MediaType::class)
                            ->required()
                            ->default(MediaType::Photo->value)
                            ->native(false)
                            ->helperText('photo = referensi asli · demo = stock preview · placeholder = belum ada foto'),
                        TextInput::make('year_label')
                            ->label('Tahun (label)')
                            ->helperText('String bebas — boleh "Reference" untuk equipment plate tanpa tanggal.'),
                        TextInput::make('related_activity_slug')
                            ->label('Slug Kegiatan Terkait')
                            ->helperText('Opsional — isi slug Activity jika foto ini mendokumentasikan kegiatan tertentu.'),
                    ])
                    ->columns(2),

                SpatieMediaLibraryFileUpload::make('photos')
                    ->label('Foto')
                    ->collection('photos')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->image()
                    ->imageEditor(),

                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("title.{$locale}")
                        ->label("Judul ({$locale})")
                        ->required($locale === 'id'),
                    Textarea::make("caption.{$locale}")
                        ->label("Caption ({$locale})")
                        ->rows(2),
                ], label: 'Konten'),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
