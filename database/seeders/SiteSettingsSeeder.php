<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageBlock;
use App\Models\SiteSettings;
use Illuminate\Database\Seeder;

/**
 * Seeds the singleton from values previously hardcoded in the SPA
 * (WhatsApp number in 3 Vue files, copyright year, disclaimer copy).
 */
final class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = SiteSettings::query()->firstOrCreate([]);
        $disclaimer = PageBlock::forKey('_global.disclaimer');

        $settings->fill([
            'whatsapp_number' => '6285155429967',
            'community_name' => ['id' => 'M.R.T — Marine Raider Tadulako', 'en' => 'M.R.T — Marine Raider Tadulako'],
            'callsign_short' => 'M.R.T',
            'copyright_holder' => 'M.R.T',
            'copyright_start_year' => 2026,
            'disclaimer' => $disclaimer
                ? ['id' => $disclaimer->getTranslation('body', 'id'), 'en' => $disclaimer->getTranslation('body', 'en')]
                : null,
        ])->save();
    }
}
