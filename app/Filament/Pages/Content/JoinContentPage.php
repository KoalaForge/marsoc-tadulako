<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Filament\Pages\Content\Schemas\Blocks;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class JoinContentPage extends AbstractContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static ?string $navigationLabel = 'Halaman Join';

    protected static ?string $title = 'Halaman Join';

    protected static ?string $slug = 'content/join';

    protected static ?int $navigationSort = 80;

    public static function pageKey(): string
    {
        return 'join';
    }

    protected function sections(): array
    {
        return [
            Blocks::section('Hero', 'hero', [
                'eyebrow' => 'Eyebrow',
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
            Blocks::section('Tentang Keanggotaan', 'about', [
                'heading' => 'Judul',
                'body' => ['Isi', 'area'],
            ]),
            Blocks::section('Syarat Keanggotaan', 'eligibility', [
                'heading' => 'Judul',
            ], [
                Blocks::items('eligibility', 'Syarat', [
                    Blocks::localized('body', 'Isi', area: true),
                ], 'body'),
            ]),
            Blocks::section('Impression Expectations', 'expectations', [
                'heading' => 'Judul',
                'body' => ['Isi', 'area'],
            ]),
            Blocks::section('Peraturan Komunitas', 'rules', [
                'heading' => 'Judul',
            ], [
                Blocks::items('rules', 'Peraturan', [
                    Blocks::localized('body', 'Isi', area: true),
                ], 'body'),
            ]),
            Blocks::section('Proses Keanggotaan', 'steps', [
                'heading' => 'Judul',
            ], [
                Blocks::items('steps', 'Langkah', [
                    Blocks::plain('value', 'Nomor'),
                    Blocks::localized('title', 'Judul langkah'),
                    Blocks::localized('body', 'Isi', area: true),
                ], 'title'),
            ]),
        ];
    }
}
