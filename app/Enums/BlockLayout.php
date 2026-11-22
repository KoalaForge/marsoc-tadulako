<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Drives which Vue BlockRenderer component a page_block renders through.
 * The component map on the frontend is static — nothing renders from a
 * dynamic string — so this is a closed, backed enum.
 */
enum BlockLayout: string implements HasLabel
{
    case PageHero = 'page_hero';
    case Prose = 'prose';
    case CardGrid = 'card_grid';
    case NumberedList = 'numbered_list';
    case NumberedSteps = 'numbered_steps';
    case BulletList = 'bullet_list';
    case DefinitionList = 'definition_list';
    case Creed = 'creed';
    case SourceList = 'source_list';
    case LogoModes = 'logo_modes';
    case FeedTeaser = 'feed_teaser';
    case Statement = 'statement';

    public function getLabel(): string
    {
        return match ($this) {
            self::PageHero => 'Hero Halaman',
            self::Prose => 'Prosa',
            self::CardGrid => 'Grid Kartu',
            self::NumberedList => 'Daftar Bernomor',
            self::NumberedSteps => 'Langkah Bernomor',
            self::BulletList => 'Daftar Poin',
            self::DefinitionList => 'Daftar Definisi',
            self::Creed => 'Creed',
            self::SourceList => 'Daftar Sumber',
            self::LogoModes => 'Mode Logo',
            self::FeedTeaser => 'Teaser Feed',
            self::Statement => 'Pernyataan',
        };
    }
}
