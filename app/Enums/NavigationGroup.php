<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Filament nav groups. Order here is presentation order — see each
 * resource/page's $navigationGroup.
 *
 * Implements HasLabel: Filament's NavigationGroup::fromEnum() falls back
 * to the enum case's bare NAME (e.g. "KontenHalaman"), not its backed
 * ->value, for any case that doesn't implement this contract.
 */
enum NavigationGroup: string implements HasLabel
{
    case KontenHalaman = 'Konten Halaman';
    case KomunitasArsip = 'Komunitas & Arsip';
    case ReferensiImpresi = 'Referensi Impresi';
    case Rekrutmen = 'Rekrutmen';
    case Pengaturan = 'Pengaturan';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function sort(): int
    {
        return match ($this) {
            self::KontenHalaman => 10,
            self::KomunitasArsip => 20,
            self::ReferensiImpresi => 30,
            self::Rekrutmen => 40,
            self::Pengaturan => 90,
        };
    }
}
