<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Filament\Pages\Content\Schemas\Blocks;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class HomeContentPage extends AbstractContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Halaman Beranda';

    protected static ?string $title = 'Halaman Beranda';

    protected static ?string $slug = 'content/home';

    protected static ?int $navigationSort = 10;

    public static function pageKey(): string
    {
        return 'home';
    }

    protected function sections(): array
    {
        return [
            Blocks::section('Hero', 'hero', [
                'eyebrow' => 'Eyebrow',
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
            Blocks::section('Formula Identitas', 'formula', [
                'heading' => 'Judul',
                'footnote' => ['Catatan penutup', 'area'],
            ], [
                Blocks::items('formula', 'Kartu formula', [
                    Blocks::plain('label', 'Label'),
                    Blocks::localized('meta', 'Meta'),
                    Blocks::localized('body', 'Deskripsi', area: true),
                ]),
            ]),
            Blocks::section('Lima Standar', 'standards', [
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ], [
                Blocks::items('standards', 'Standar', [
                    Blocks::plain('value', 'Nomor'),
                    Blocks::plain('label', 'Nama'),
                    Blocks::localized('body', 'Deskripsi', area: true),
                ]),
            ]),
            Blocks::section('Creed', 'creed', [
                'footnote' => ['Kutipan penutup', 'area'],
            ], [
                Blocks::items('creed', 'Baris creed', [
                    Blocks::plain('label', 'Teks'),
                    Blocks::weight(),
                ]),
            ]),
            Blocks::section('Kegiatan Terbaru', 'latest-activities', [
                'heading' => 'Judul',
                'footnote' => ['Catatan', 'area'],
            ]),
            Blocks::section('Teaser Impression', 'impression-teaser', [
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ], [
                Blocks::image(
                    'home.impression-teaser',
                    'Foto (opsional)',
                    'Tampil di samping teks. Kosongkan dan foto pertama dari Weapon Variant / Era yang dipublikasikan dipakai otomatis; jika itu juga kosong, bagian ini hanya berisi teks.',
                ),
            ]),
            Blocks::section('Dokumentasi Terbaru', 'latest-media', [
                'heading' => 'Judul',
            ]),
            Blocks::section('Ajakan Bergabung', 'final-cta', [
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
        ];
    }
}
