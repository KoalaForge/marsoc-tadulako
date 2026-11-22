<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Filament\Pages\Content\Schemas\Blocks;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

final class PersonnelContentPage extends AbstractContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Halaman Personnel';

    protected static ?string $title = 'Halaman Personnel';

    protected static ?string $slug = 'content/personnel';

    protected static ?int $navigationSort = 20;

    public static function pageKey(): string
    {
        return 'personnel';
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
