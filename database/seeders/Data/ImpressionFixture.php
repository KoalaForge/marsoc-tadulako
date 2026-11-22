<?php

declare(strict_types=1);

namespace Database\Seeders\Data;

/**
 * Milestone-2 transcription of src/data/impression.js — throwaway once the
 * real Era/WeaponVariant/ImpressionSpec models exist (milestone 6).
 */
final class ImpressionFixture
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function eras(): array
    {
        $base = '/images/fixtures/unit/eras';

        return [
            [
                'key' => 'early-marsoc',
                'name' => 'Early MARSOC (Afghanistan)',
                'images' => ["{$base}/early-marsoc-1.jpg", "{$base}/early-marsoc-2.jpg", "{$base}/early-marsoc-3.jpg"],
                'uniform' => [
                    'en' => 'MCCUU or FROG MARPAT Woodland/Desert; alt. Crye G3 Woodland (ordered by MARSOC from 2010).',
                    'id' => 'MCCUU atau FROG MARPAT Woodland/Desert; alternatif Crye G3 Woodland (dipesan MARSOC sejak 2010).',
                ],
                'gear' => [
                    'en' => 'Coyote Brown webbing — the standard pairing with MARPAT.',
                    'id' => 'Webbing Coyote Brown — pasangan standar untuk MARPAT.',
                ],
                'helmet' => [
                    'en' => 'MARPAT cover or coyote/tan shell.',
                    'id' => 'Cover MARPAT atau shell coyote/tan.',
                ],
            ],
            [
                'key' => 'multicam',
                'name' => 'MultiCam (pre-2025)',
                'images' => ["{$base}/multicam-1.jpg", "{$base}/multicam-2.jpg", "{$base}/multicam-3.jpg"],
                'uniform' => [
                    'en' => 'MultiCam combat shirt & pant, matching Raiders to other SOF units.',
                    'id' => 'Combat shirt & pant MultiCam, menyelaraskan Raiders dengan unit SOF lain.',
                ],
                'gear' => [
                    'en' => 'MultiCam or coyote; high-cut helmet with MultiCam cover.',
                    'id' => 'MultiCam atau coyote; helm high-cut dengan cover MultiCam.',
                ],
                'helmet' => [
                    'en' => 'Over-ear headset mounted on the helmet rail.',
                    'id' => 'Headset over-ear terpasang di rail helm.',
                ],
            ],
            [
                'key' => 'return-to-marpat',
                'name' => 'Return to MARPAT (2025 →)',
                'images' => ["{$base}/return-to-marpat-1.jpg", "{$base}/return-to-marpat-2.jpg"],
                'uniform' => [
                    'en' => 'FROG MARPAT as an interim uniform, per the MARSOC Commandant memo (20 Sep 2024, effective 1 Jan 2025) ending MultiCam.',
                    'id' => 'FROG MARPAT sebagai seragam interim, sesuai memo Komandan MARSOC (20 Sep 2024, efektif 1 Jan 2025) yang mengakhiri MultiCam.',
                ],
                'gear' => ['en' => 'OD Green.', 'id' => 'OD Green.'],
                'helmet' => [
                    'en' => 'OD/Ranger Green shell or cover.',
                    'id' => 'Shell atau cover OD/Ranger Green.',
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function weapons(): array
    {
        $unit = '/images/fixtures/unit';
        $extra = '/images/fixtures/unit/weapons-extra';

        return [
            [
                'categoryKey' => 'rifle',
                'category' => 'Rifle',
                'items' => [
                    ['key' => 'mk18-dd-ris2', 'name' => 'Mk18 Mod 1 — Daniel Defense RIS II (classic)', 'images' => ["{$extra}/mk18-dd-ris2-1.jpg"]],
                    ['key' => 'mk18-urgi', 'name' => 'Mk18 / CQBR 10.3" — Geissele Mk16 9.3" (modern, "URG-I style")', 'images' => ["{$unit}/mk18-cqbr-10-3-geissele-mk16.jpg"]],
                    ['key' => 'scar-mk16-17', 'name' => 'SCAR Mk16 / Mk17', 'images' => ["{$unit}/scar-mk16-mk17.jpg"]],
                    ['key' => 'cqbr-block-ii', 'name' => 'CQBR Block II', 'images' => ["{$unit}/cqbr-block-ii.jpg"]],
                ],
            ],
            [
                'categoryKey' => 'handgun',
                'category' => 'Pistol',
                'items' => [
                    ['key' => 'glock17', 'name' => 'Glock 17', 'images' => ["{$unit}/glock-17.jpg"]],
                    ['key' => 'glock19', 'name' => 'Glock 19', 'images' => ["{$unit}/glock-19.jpg"]],
                ],
            ],
            [
                'categoryKey' => 'machinegun',
                'category' => 'Machine Gun',
                'items' => [
                    ['key' => 'mk48', 'name' => 'Mk48 Mod 1', 'images' => ["{$unit}/mk48-mod1.jpg"]],
                ],
            ],
            [
                'categoryKey' => 'shotgun',
                'category' => 'Shotgun',
                'items' => [
                    ['key' => 'rem870', 'name' => 'Remington 870', 'images' => ["{$extra}/remington-870-1.jpg", "{$extra}/remington-870-2.jpg"]],
                    ['key' => 'moss500', 'name' => 'Mossberg 500', 'images' => ["{$extra}/mossberg-500-1.jpg", "{$extra}/mossberg-500-2.jpg"]],
                ],
            ],
            [
                'categoryKey' => 'sniper',
                'category' => 'Sniper',
                'items' => [
                    ['key' => 'mk13', 'name' => 'Mk13 Mod 7', 'images' => ["{$extra}/mk13-mod7-1.jpg", "{$extra}/mk13-mod7-2.jpg"]],
                    ['key' => 'm110', 'name' => 'M110 SASS', 'images' => ["{$unit}/m110-sass.jpg"]],
                    ['key' => 'mk11', 'name' => 'Mk11 SR-25', 'images' => ["{$extra}/mk11-sr25-1.jpg", "{$extra}/mk11-sr25-2.jpg"]],
                    ['key' => 'barrett', 'name' => 'Barrett M82', 'images' => ["{$extra}/barrett-m82-1.jpg", "{$extra}/barrett-m82-2.jpg"]],
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function headgear(): array
    {
        return [
            [
                'label' => 'Helmet',
                'detail' => [
                    'en' => 'Ops-Core FAST High Cut (SOCOM contract, 2019) or a non-ballistic bump base — the common Airsoft replica starting point. ECH/LWH/MICH mid-cut for training/Marine-general impressions.',
                    'id' => 'Ops-Core FAST High Cut (kontrak SOCOM, 2019) atau bump base non-balistik — titik awal replika Airsoft yang umum. ECH/LWH/MICH mid-cut untuk impresi training/Marine umum.',
                ],
            ],
            [
                'label' => 'Headset',
                'detail' => [
                    'en' => '3M Peltor ComTac or TCI Liberator over-ear, mounted on the rail — the most recognizable Raider silhouette detail.',
                    'id' => '3M Peltor ComTac atau TCI Liberator over-ear, terpasang di rail — detail siluet Raider yang paling dikenali.',
                ],
            ],
            [
                'label' => 'NVG Mount',
                'detail' => [
                    'en' => 'Three-hole shroud with a Wilcox L4 G24 or Norotos arm. Dummy PVS-14/15/31 is enough for the visual; no real NVG required.',
                    'id' => 'Shroud tiga lubang dengan lengan Wilcox L4 G24 atau Norotos. Dummy PVS-14/15/31 sudah cukup untuk visual; tidak perlu NVG asli.',
                ],
            ],
            [
                'label' => 'Patch Placement',
                'detail' => [
                    'en' => 'M.R.T patch on the sleeve, carrier front panel, and helmet side/rear for IR patch and callsign tape.',
                    'id' => 'Patch M.R.T di lengan, panel depan carrier, dan sisi/belakang helm untuk IR patch dan callsign tape.',
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function eyeProtection(): array
    {
        return [
            [
                'label' => 'Standard',
                'detail' => [
                    'en' => 'The US military uses APEL (Authorized Protective Eyewear List) — tested glasses and goggles meeting ANSI Z87 + MIL-PRF-31013, in place since 2006.',
                    'id' => 'Militer AS memakai APEL (Authorized Protective Eyewear List) — kacamata dan goggle teruji standar ANSI Z87 + MIL-PRF-31013, berlaku sejak 2006.',
                ],
            ],
            [
                'label' => 'Approved Brands',
                'detail' => ['en' => 'ESS, Wiley X, Revision, Oakley SI.', 'id' => 'ESS, Wiley X, Revision, Oakley SI.'],
            ],
            [
                'label' => 'Airsoft Rule',
                'detail' => [
                    'en' => 'Use genuine impact-rated product (Z87+ marking). Avoid replicas that only look the part.',
                    'id' => 'Pakai produk asli ber-rating impact (tanda Z87+). Hindari replika yang cuma mirip tampilannya.',
                ],
            ],
            [
                'label' => 'Gatorz Magnum',
                'detail' => [
                    'en' => 'An aluminum sunglass frame popular among Raiders and SOF. Use the Magnum Z / ANSI variant (Z87+ marking) with factory lenses — non-ANSI versions and replicas are not safe for Airsoft.',
                    'id' => 'Sunglass frame aluminium yang populer di kalangan Raiders dan SOF. Pakai varian Magnum Z / ANSI (tanda Z87+) dengan lensa bawaan pabrik — versi non-ANSI dan replika tidak aman untuk Airsoft.',
                ],
            ],
            [
                'label' => 'Venue Rule',
                'detail' => [
                    'en' => 'Follow venue and marshal rules, including lower-face protection when required.',
                    'id' => 'Ikuti aturan venue dan marshal, termasuk perlindungan wajah bagian bawah bila diminta.',
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function sources(): array
    {
        return [
            'Tier Three Tactical — Marine Raider Loadout',
            'MARSOC — MMAC Individual Equipment List',
            'Wikipedia — List of weapons of the United States Marine Corps; MARPAT; Flame Resistant Organizational Gear',
            'Military.com Kit Up! / Soldier Systems Daily — MARSOC uniform orders and the 2024 MultiCam memo',
            'Marine Corps Times — SOCOM helmet and headset contracts (2019)',
            'Wikipedia — Authorized Protective Eyewear List',
            'Gatorz Eyewear — Magnum ANSI Z87.1+ specifications',
        ];
    }
}
