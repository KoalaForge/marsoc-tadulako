<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Filament\Pages\Content\Schemas\Blocks;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class ImpressionContentPage extends AbstractContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Halaman Impression';

    protected static ?string $title = 'Halaman Impression';

    protected static ?string $slug = 'content/impression';

    protected static ?int $navigationSort = 30;

    public static function pageKey(): string
    {
        return 'impression';
    }

    protected function sections(): array
    {
        return [
            Blocks::section('Hero', 'hero', [
                'eyebrow' => 'Eyebrow',
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
            Blocks::section('Sejarah & Organisasi', 'history', [
                'heading' => 'Judul',
                'body' => ['Isi', 'area'],
            ]),
            Blocks::section('Era Seragam (pengantar)', 'eras', [
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
            Blocks::section('Weapon Platforms (pengantar)', 'weapons', [
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
            Blocks::section('Headgear & NVG', 'headgear', [
                'heading' => 'Judul',
            ], [
                Blocks::items('headgear', 'Baris', [
                    Blocks::plain('label', 'Label (EN saja)'),
                    Blocks::localized('body', 'Isi', area: true),
                ]),
            ]),
            Blocks::section('Eye Protection', 'eye-protection', [
                'heading' => 'Judul',
            ], [
                Blocks::items('eye-protection', 'Baris', [
                    Blocks::plain('label', 'Label (EN saja)'),
                    Blocks::localized('body', 'Isi', area: true),
                ]),
            ]),
            Blocks::section('Reference Library', 'library', [
                'heading' => 'Judul',
                'subheading' => ['Pengantar', 'area'],
                'footnote' => ['Catatan', 'area'],
            ], [
                Blocks::items('library', 'Sumber', [
                    Blocks::plain('label', 'Sumber'),
                ]),
            ]),
        ];
    }
}
