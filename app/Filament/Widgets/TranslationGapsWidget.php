<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Support\TranslationGaps;
use Filament\Widgets\Widget;

final class TranslationGapsWidget extends Widget
{
    protected string $view = 'filament.widgets.translation-gaps';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 10;

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        return ['gaps' => TranslationGaps::scan()];
    }
}
