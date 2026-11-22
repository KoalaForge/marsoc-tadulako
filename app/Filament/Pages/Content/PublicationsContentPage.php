<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Filament\Pages\Content\Schemas\Blocks;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class PublicationsContentPage extends AbstractContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Halaman Publikasi';

    protected static ?string $title = 'Halaman Publikasi';

    protected static ?string $slug = 'content/publications';

    protected static ?int $navigationSort = 70;

    public static function pageKey(): string
    {
        return 'publications';
    }

    protected function sections(): array
    {
        return [
            Blocks::section('Hero', 'hero', [
                'eyebrow' => 'Eyebrow',
                'heading' => 'Judul',
                'subheading' => ['Sub-judul', 'area'],
            ]),
        ];
    }
}
