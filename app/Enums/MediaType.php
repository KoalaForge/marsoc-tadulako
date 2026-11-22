<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * A different axis from `is_synthetic` (provenance): this drives the
 * render path (Preview badge / nothing / "documentation pending" empty
 * state). Collapsing the two would let a demo-data purge delete genuine
 * equipment reference photos, which ship as `type: photo`.
 */
enum MediaType: string implements HasLabel
{
    case Photo = 'photo';
    case Demo = 'demo';
    case Placeholder = 'placeholder';

    public function getLabel(): string
    {
        return match ($this) {
            self::Photo => 'Foto Asli',
            self::Demo => 'Demo / Preview',
            self::Placeholder => 'Placeholder',
        };
    }
}
