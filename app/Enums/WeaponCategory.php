<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * EN-only by design rule (DESIGN.md:212 in the source frontend) — weapon
 * category names stay identical in both locales, so this is a backed
 * enum rather than a translatable collection.
 */
enum WeaponCategory: string implements HasLabel
{
    case Rifle = 'rifle';
    case Handgun = 'handgun';
    case MachineGun = 'machinegun';
    case Shotgun = 'shotgun';
    case Sniper = 'sniper';

    public function getLabel(): string
    {
        return match ($this) {
            self::Rifle => 'Rifle',
            self::Handgun => 'Pistol',
            self::MachineGun => 'Machine Gun',
            self::Shotgun => 'Shotgun',
            self::Sniper => 'Sniper',
        };
    }
}
