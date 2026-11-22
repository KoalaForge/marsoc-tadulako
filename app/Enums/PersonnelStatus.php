<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PersonnelStatus: string implements HasLabel
{
    case Active = 'active';
    case Probationary = 'probationary';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Probationary => 'Masa Percobaan',
        };
    }
}
