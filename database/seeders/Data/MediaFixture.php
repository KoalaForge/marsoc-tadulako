<?php

declare(strict_types=1);

namespace Database\Seeders\Data;

/**
 * Milestone-2 transcription of src/data/media.js — throwaway once the real
 * MediaItem model + media library exist (milestone 6). Photos are plain
 * public URL strings (copied into public/images/fixtures), matching the
 * shape medialibrary-served URLs will eventually take.
 */
final class MediaFixture
{
    public const CATEGORIES = [
        'Equipment Photography',
        'Field Activities',
        'Training Documentation',
        'Skirmish Documentation',
        'MilSim Events',
        'Team Photography',
        'Individual Portraits',
        'Historical Impression',
    ];

    /**
     * @return array<string, array<string, string>>
     */
    public static function categoryLabels(): array
    {
        return [
            'en' => [
                'Equipment Photography' => 'Equipment Photography',
                'Field Activities' => 'Field Activities',
                'Training Documentation' => 'Training Documentation',
                'Skirmish Documentation' => 'Skirmish Documentation',
                'MilSim Events' => 'MilSim Events',
                'Team Photography' => 'Team Photography',
                'Individual Portraits' => 'Individual Portraits',
                'Historical Impression' => 'Historical Impression',
            ],
            'id' => [
                'Equipment Photography' => 'Foto Equipment',
                'Field Activities' => 'Kegiatan Lapangan',
                'Training Documentation' => 'Dokumentasi Latihan',
                'Skirmish Documentation' => 'Dokumentasi Skirmish',
                'MilSim Events' => 'Kegiatan MilSim',
                'Team Photography' => 'Foto Tim',
                'Individual Portraits' => 'Potret Individu',
                'Historical Impression' => 'Impresi Historis',
            ],
        ];
    }

    public static function categoryLabel(string $category, string $locale): string
    {
        return self::categoryLabels()[$locale][$category]
            ?? self::categoryLabels()['en'][$category]
            ?? $category;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        $unit = '/images/fixtures/unit';
        $demo = '/images/fixtures/media-demo';

        return [
            [
                'id' => 'eq-mk18-urgi',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/mk18-cqbr-10-3-geissele-mk16.jpg"],
                'title' => ['en' => 'Mk18 / CQBR 10.3" — Geissele Mk16 9.3"', 'id' => 'Mk18 / CQBR 10.3" — Geissele Mk16 9.3"'],
                'caption' => [
                    'en' => 'Modern "URG-I style" reference build. Real-steel reference photo — M.R.T uses airsoft replicas only.',
                    'id' => 'Build referensi "gaya URG-I" modern. Foto referensi real-steel — M.R.T hanya memakai replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'eq-cqbr-block-ii',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/cqbr-block-ii.jpg"],
                'title' => ['en' => 'CQBR Block II', 'id' => 'CQBR Block II'],
                'caption' => [
                    'en' => 'Classic CQBR reference configuration. Real-steel reference photo — M.R.T uses airsoft replicas only.',
                    'id' => 'Konfigurasi referensi CQBR klasik. Foto referensi real-steel — M.R.T hanya memakai replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'eq-scar',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/scar-mk16-mk17.jpg"],
                'title' => ['en' => 'FN SCAR — Mk16 / Mk17', 'id' => 'FN SCAR — Mk16 / Mk17'],
                'caption' => [
                    'en' => 'Mk16 (5.56) and Mk17 (7.62) reference platforms. Real-steel reference photo — airsoft replicas only.',
                    'id' => 'Platform referensi Mk16 (5.56) dan Mk17 (7.62). Foto referensi real-steel — hanya replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'eq-mk48',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/mk48-mod1.jpg"],
                'title' => ['en' => 'Mk48 Mod 1', 'id' => 'Mk48 Mod 1'],
                'caption' => [
                    'en' => 'Machine-gun reference platform. Real-steel reference photo — airsoft replicas only.',
                    'id' => 'Platform referensi machine gun. Foto referensi real-steel — hanya replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'eq-m110',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/m110-sass.jpg"],
                'title' => ['en' => 'M110 SASS', 'id' => 'M110 SASS'],
                'caption' => [
                    'en' => 'Semi-automatic sniper system reference. Real-steel reference photo — airsoft replicas only.',
                    'id' => 'Referensi semi-automatic sniper system. Foto referensi real-steel — hanya replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'eq-glock17',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/glock-17.jpg"],
                'title' => ['en' => 'Glock 17', 'id' => 'Glock 17'],
                'caption' => [
                    'en' => 'Sidearm reference platform. Real-steel reference photo — airsoft replicas only.',
                    'id' => 'Platform referensi sidearm. Foto referensi real-steel — hanya replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'eq-glock19',
                'category' => 'Equipment Photography',
                'type' => 'photo',
                'photos' => ["{$unit}/glock-19.jpg"],
                'title' => ['en' => 'Glock 19', 'id' => 'Glock 19'],
                'caption' => [
                    'en' => 'Compact sidearm reference platform. Real-steel reference photo — airsoft replicas only.',
                    'id' => 'Platform referensi sidearm compact. Foto referensi real-steel — hanya replika airsoft.',
                ],
                'year' => 'Reference',
            ],
            [
                'id' => 'ph-grid-zero',
                'category' => 'Training Documentation',
                'type' => 'demo',
                'photos' => ["{$demo}/training-day-1.jpg", "{$demo}/training-day-2.jpg"],
                'title' => ['en' => 'Grid Zero: Foundational Training Day', 'id' => 'Grid Zero: Hari Latihan Dasar'],
                'caption' => [
                    'en' => 'Documentation from an M.R.T training day.',
                    'id' => 'Dokumentasi hari latihan M.R.T.',
                ],
                'year' => '2025',
                'relatedActivity' => 'grid-zero-training-day',
            ],
            [
                'id' => 'ph-anoa-shield',
                'category' => 'MilSim Events',
                'type' => 'demo',
                'photos' => ["{$demo}/milsim-event-1.jpg", "{$demo}/milsim-event-2.jpg"],
                'title' => ['en' => 'Operation Anoa Shield', 'id' => 'Operation Anoa Shield'],
                'caption' => [
                    'en' => 'Documentation from an M.R.T milsim event.',
                    'id' => 'Dokumentasi kegiatan milsim M.R.T.',
                ],
                'year' => '2025',
                'relatedActivity' => 'operation-anoa-shield',
            ],
            [
                'id' => 'ph-tadulako-cup',
                'category' => 'Skirmish Documentation',
                'type' => 'demo',
                'photos' => ["{$demo}/skirmish-1.jpg", "{$demo}/skirmish-2.jpg"],
                'title' => ['en' => 'Tadulako Cup Skirmish Series — Round 3', 'id' => 'Tadulako Cup Skirmish Series — Ronde 3'],
                'caption' => [
                    'en' => 'Documentation from an M.R.T skirmish.',
                    'id' => 'Dokumentasi skirmish M.R.T.',
                ],
                'year' => '2025',
                'relatedActivity' => 'tadulako-cup-skirmish-3',
            ],
            [
                'id' => 'ph-joint-field',
                'category' => 'Field Activities',
                'type' => 'demo',
                'photos' => ["{$demo}/joint-field-1.jpg", "{$demo}/joint-field-2.jpg"],
                'title' => ['en' => 'Joint Field Exercise with Allied Impression Units', 'id' => 'Latihan Gabungan dengan Unit Impresi Sekutu'],
                'caption' => [
                    'en' => 'Documentation from the joint exercise with allied impression units.',
                    'id' => 'Dokumentasi latihan gabungan dengan unit impresi sekutu.',
                ],
                'year' => '2025',
                'relatedActivity' => 'joint-field-exercise',
            ],
            [
                'id' => 'ph-patch-night',
                'category' => 'Team Photography',
                'type' => 'demo',
                'photos' => ["{$demo}/team-photo-1.jpg", "{$demo}/team-photo-2.jpg"],
                'title' => ['en' => 'Patch & Brotherhood Night', 'id' => 'Malam Patch & Brotherhood'],
                'caption' => [
                    'en' => 'Group photography of M.R.T members.',
                    'id' => 'Foto bersama anggota M.R.T.',
                ],
                'year' => '2025',
                'relatedActivity' => 'patch-and-brotherhood-night',
            ],
            [
                'id' => 'ph-portraits',
                'category' => 'Individual Portraits',
                'type' => 'demo',
                'photos' => ["{$demo}/portrait-1.jpg", "{$demo}/portrait-2.jpg"],
                'title' => ['en' => 'Member Portrait Series', 'id' => 'Seri Potret Anggota'],
                'caption' => [
                    'en' => 'Individual portrait series of M.R.T members.',
                    'id' => 'Seri potret individu anggota M.R.T.',
                ],
                'year' => '2025',
            ],
            [
                'id' => 'ph-historical',
                'category' => 'Historical Impression',
                'type' => 'demo',
                'photos' => ["{$demo}/historical-impression-1.jpg", "{$demo}/historical-impression-2.jpg"],
                'title' => ['en' => 'Era Study Photography', 'id' => 'Foto Studi Era'],
                'caption' => [
                    'en' => 'Era study photography comparing uniform and equipment eras.',
                    'id' => 'Foto studi perbandingan era seragam dan equipment.',
                ],
                'year' => '2025',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function byCategory(?string $category): array
    {
        if (! $category || $category === 'All') {
            return self::all();
        }

        return array_values(array_filter(
            self::all(),
            fn (array $item): bool => $item['category'] === $category,
        ));
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(string $id): ?array
    {
        foreach (self::all() as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }

        return null;
    }
}
