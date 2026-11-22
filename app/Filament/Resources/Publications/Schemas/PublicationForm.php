<?php

declare(strict_types=1);

namespace App\Filament\Resources\Publications\Schemas;

use App\Filament\Shared\Schemas\LocaleTabs;
use App\Models\PublicationCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Artikel')
                    ->schema([
                        TextInput::make('slug')
                            ->label('Slug')
                            ->helperText('Kosongkan untuk dibuat otomatis dari judul (ID).')
                            ->unique(ignoreRecord: true),
                        Select::make('publication_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'slug')
                            ->getOptionLabelFromRecordUsing(fn (PublicationCategory $category): string => $category->getTranslation('name', app()->getLocale()))
                            ->searchable()
                            ->native(false),
                        TextInput::make('author_callsign')
                            ->label('Penulis (callsign)')
                            ->helperText('Teks biasa, bukan relasi — tampil apa adanya, tidak ditautkan ke profil.'),
                        DatePicker::make('published_on')
                            ->label('Tanggal Terbit'),
                        TextInput::make('related_activity_slug')
                            ->label('Slug Kegiatan Terkait')
                            ->helperText('Opsional — jika artikel ini adalah laporan dari sebuah Activity.'),
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Banner (opsional)')
                            ->collection('cover')
                            ->image()
                            ->imageEditor()
                            ->imageAspectRatio('16:9')
                            ->automaticallyCropImagesToAspectRatio()
                            ->maxSize(8192)
                            ->helperText('Rasio 16:9, maks 8 MB. Kosongkan jika artikel tidak perlu banner.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("title.{$locale}")
                        ->label("Judul ({$locale})")
                        ->required($locale === 'id'),
                    Textarea::make("excerpt.{$locale}")
                        ->label("Ringkasan ({$locale})")
                        ->rows(2)
                        ->required($locale === 'id'),
                    RichEditor::make("content.{$locale}")
                        ->label("Isi ({$locale})")
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike', 'link'],
                            ['h2', 'h3'],
                            ['blockquote', 'bulletList', 'orderedList'],
                            ['attachFiles'],
                            ['undo', 'redo'],
                        ])
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('publications')
                        ->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->fileAttachmentsMaxSize(5120)
                        ->required($locale === 'id')
                        ->helperText('Sisipkan gambar dengan tombol lampiran; gambar tampil selebar kolom teks.'),
                ], label: 'Konten'),

                Repeater::make('sources')
                    ->label('Sumber')
                    ->simple(
                        TextInput::make('label')->label('Sumber')->required(),
                    )
                    ->dehydrateStateUsing(fn (array $state): array => array_values($state))
                    ->addActionLabel('Tambah Sumber'),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
