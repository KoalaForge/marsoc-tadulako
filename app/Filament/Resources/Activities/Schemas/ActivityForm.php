<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Schemas;

use App\Filament\Shared\Schemas\LocaleTabs;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Personnel;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityForm
{
    /**
     * Activity picker options for forms that reference an activity by slug.
     *
     * @return array<string, string> slug => title (ID)
     */
    public static function slugOptions(): array
    {
        return Activity::query()->orderBy('created_at', 'desc')->get()
            ->mapWithKeys(fn (Activity $a): array => [
                $a->slug => (string) ($a->getTranslation('title', 'id', useFallbackLocale: true)),
            ])
            ->all();
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Kegiatan')
                    ->schema([
                        TextInput::make('slug')
                            ->label('Slug')
                            ->helperText('Kosongkan untuk dibuat otomatis dari judul (ID).')
                            ->unique(ignoreRecord: true),
                        Select::make('activity_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'slug')
                            ->getOptionLabelFromRecordUsing(fn (ActivityCategory $category): string => $category->getTranslation('name', app()->getLocale()))
                            ->searchable()
                            ->native(false),
                        DatePicker::make('occurred_on')
                            ->label('Tanggal')
                            ->required(),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->helperText('String biasa — tidak diterjemahkan.'),
                        TextInput::make('grid')
                            ->label('Grid Reference'),
                        TextInput::make('participants')
                            ->label('Jumlah Peserta')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns(2),

                Section::make('Banner')
                    ->description('Gambar utama kegiatan — tampil di atas halaman detail dan di kartu kegiatan.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('banner')
                            ->label('Gambar banner')
                            ->collection('banner')
                            ->image()
                            ->imageEditor()
                            ->imageAspectRatio('16:9')
                            ->automaticallyCropImagesToAspectRatio()
                            ->maxSize(8192)
                            ->helperText('Rasio 16:9, maks 8 MB. Dipotong otomatis.'),
                    ]),

                Section::make('Anggota Terlibat')
                    ->schema([
                        CheckboxList::make('related_member_callsigns')
                            ->label('Anggota')
                            ->options(fn (): array => Personnel::query()->orderBy('sort')
                                ->get()
                                ->mapWithKeys(fn (Personnel $p): array => [$p->callsign => "{$p->callsign} — {$p->member_id}"])
                                ->all())
                            ->columns(2)
                            ->bulkToggleable()
                            ->helperText('Array string biasa — bukan relasi. Tidak ada sinkronisasi dua arah.'),
                    ]),

                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("title.{$locale}")
                        ->label("Judul ({$locale})")
                        ->required($locale === 'id'),
                    Textarea::make("overview.{$locale}")
                        ->label("Ikhtisar singkat ({$locale})")
                        ->rows(3)
                        ->required($locale === 'id'),
                    RichEditor::make("content.{$locale}")
                        ->label("Isi Kegiatan ({$locale})")
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike', 'link'],
                            ['h2', 'h3'],
                            ['blockquote', 'bulletList', 'orderedList'],
                            ['undo', 'redo'],
                        ])
                        ->helperText('Cerita lengkap kegiatan. Ikhtisar di atas dipakai sebagai ringkasan di kartu.'),
                ], label: 'Konten'),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
