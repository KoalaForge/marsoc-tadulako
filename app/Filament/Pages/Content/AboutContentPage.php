<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Filament\Pages\Content\Schemas\Blocks;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class AboutContentPage extends AbstractContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?string $navigationLabel = 'Halaman About';

    protected static ?string $title = 'Halaman About';

    protected static ?string $slug = 'content/about';

    protected static ?int $navigationSort = 15;

    public static function pageKey(): string
    {
        return 'about';
    }

    protected function sections(): array
    {
        return [
            Blocks::section('Hero', 'hero', [
                'eyebrow' => 'Eyebrow',
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
            Blocks::section('Kisah Kami', 'story', [
                'heading' => 'Judul',
                'body' => ['Isi', 'area'],
            ]),
            Blocks::section('Misi & Visi', 'mission-vision', [
                'heading' => 'Judul',
            ], [
                Blocks::items('mission-vision', 'Butir', [
                    Blocks::localized('label', 'Label'),
                    Blocks::localized('body', 'Isi', area: true),
                ]),
            ]),
            Blocks::section('Nilai-Nilai', 'values', [
                'heading' => 'Judul',
            ], [
                Blocks::items('values', 'Nilai', [
                    Blocks::plain('label', 'Nama'),
                    Blocks::localized('body', 'Deskripsi', area: true),
                ]),
            ]),
            Blocks::section('Identitas', 'identity', [
                'heading' => 'Judul',
                'body' => ['Isi', 'area'],
                'footnote' => ['Catatan', 'area'],
            ]),
            Blocks::section('Mode Logo', 'logo-modes', [], [
                Blocks::items('logo-modes', 'Mode', [
                    Blocks::plain('label', 'Nama'),
                    Blocks::plain('value', 'Slot logo (SiteSettings)')
                        ->helperText('logo_color, logo_gold_on_black, logo_white_on_black, dst.'),
                    Blocks::localized('body', 'Penggunaan', area: true),
                ]),
            ]),
        ];
    }
}
