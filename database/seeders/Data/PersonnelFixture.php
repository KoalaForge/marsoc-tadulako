<?php

declare(strict_types=1);

namespace Database\Seeders\Data;

/**
 * Milestone-2 transcription of src/data/personnel.js — throwaway once the
 * real Personnel model + seeder exist (milestone 6). Field names match the
 * frontend's camelCase exactly so the Vue pages need no prop reshaping.
 */
final class PersonnelFixture
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            [
                'callsign' => 'RAIDER-01',
                'memberId' => 'MRT-001',
                'role' => ['en' => 'Founder / Commander', 'id' => 'Pendiri / Komandan'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-01',
                'impressionEra' => ['en' => 'Return to MARPAT (2025 →)', 'id' => 'Return to MARPAT (2025 →)'],
                'impressionFocus' => ['en' => 'MARSOC — Raider Element', 'id' => 'MARSOC — Elemen Raider'],
                'primaryPlatform' => 'Mk18 Mod 1',
                'secondaryPlatform' => 'Glock 19',
                'grid' => '00.89°S 119.87°E',
                'bio' => [
                    'en' => 'Stood up M.R.T to give Sulawesi Tengah airsoft players a disciplined, source-checked MARSOC impression community of their own.',
                    'id' => 'Mendirikan M.R.T agar pemain airsoft Sulawesi Tengah punya komunitas impresi MARSOC sendiri yang disiplin dan referensinya terverifikasi.',
                ],
            ],
            [
                'callsign' => 'SPIRITUS-02',
                'memberId' => 'MRT-002',
                'role' => ['en' => 'Executive Officer', 'id' => 'Wakil Komandan'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-01',
                'impressionEra' => ['en' => 'MultiCam (before 2025)', 'id' => 'MultiCam (sebelum 2025)'],
                'impressionFocus' => ['en' => 'MARSOC — Team Lead', 'id' => 'MARSOC — Team Lead'],
                'primaryPlatform' => 'SCAR Mk16',
                'secondaryPlatform' => 'Glock 17',
                'grid' => '00.89°S 119.87°E',
                'bio' => [
                    'en' => 'Runs day-to-day operations and keeps the roster, schedule, and standards aligned.',
                    'id' => 'Menjalankan operasional harian serta menjaga roster, jadwal, dan standar tetap selaras.',
                ],
            ],
            [
                'callsign' => 'TADULAKO-04',
                'memberId' => 'MRT-003',
                'role' => ['en' => 'Logistics Officer', 'id' => 'Perwira Logistik'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-02',
                'impressionEra' => ['en' => 'Return to MARPAT (2025 →)', 'id' => 'Return to MARPAT (2025 →)'],
                'impressionFocus' => ['en' => 'Logistics / Combat Support', 'id' => 'Logistik / Combat Support'],
                'primaryPlatform' => 'Mk18 / CQBR 10.3"',
                'secondaryPlatform' => 'Glock 19',
                'grid' => '01.40°S 120.10°E',
                'bio' => [
                    'en' => 'Manages gear checks, consumables, and field logistics ahead of every training day.',
                    'id' => 'Mengurus pengecekan gear, consumable, dan logistik lapangan sebelum setiap training day.',
                ],
            ],
            [
                'callsign' => 'ANOA-05',
                'memberId' => 'MRT-004',
                'role' => ['en' => 'Corpsman / Medical', 'id' => 'Corpsman / Medis'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-02',
                'impressionEra' => ['en' => 'MultiCam (before 2025)', 'id' => 'MultiCam (sebelum 2025)'],
                'impressionFocus' => ['en' => 'Corpsman / Medical', 'id' => 'Corpsman / Medis'],
                'primaryPlatform' => 'Glock 19',
                'secondaryPlatform' => '—',
                'grid' => '00.89°S 119.87°E',
                'bio' => [
                    'en' => 'Carries the aid bag and runs safety briefings before every field exercise.',
                    'id' => 'Membawa aid bag dan memimpin safety briefing sebelum setiap latihan lapangan.',
                ],
            ],
            [
                'callsign' => 'SENTINEL-06',
                'memberId' => 'MRT-005',
                'role' => ['en' => 'Communications', 'id' => 'Komunikasi'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-03',
                'impressionEra' => ['en' => 'Early MARSOC (Afghanistan)', 'id' => 'Early MARSOC (Afghanistan)'],
                'impressionFocus' => ['en' => 'Communications', 'id' => 'Komunikasi'],
                'primaryPlatform' => 'Mk48 Mod 1',
                'secondaryPlatform' => 'Glock 17',
                'grid' => '01.40°S 120.10°E',
                'bio' => [
                    'en' => 'Owns the comms plan, radio nets, and callsign discipline on the field.',
                    'id' => 'Memegang comms plan, radio net, dan disiplin callsign di lapangan.',
                ],
            ],
            [
                'callsign' => 'VANGUARD-07',
                'memberId' => 'MRT-006',
                'role' => ['en' => 'Member', 'id' => 'Anggota'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-04',
                'impressionEra' => ['en' => 'Return to MARPAT (2025 →)', 'id' => 'Return to MARPAT (2025 →)'],
                'impressionFocus' => ['en' => 'MARSOC — DM Element', 'id' => 'MARSOC — Elemen DM'],
                'primaryPlatform' => 'M110 SASS',
                'secondaryPlatform' => 'Glock 19',
                'grid' => '00.89°S 119.87°E',
                'bio' => [
                    'en' => 'Builds precision-rifle loadouts and runs overwatch during skirmishes.',
                    'id' => 'Membangun loadout precision rifle dan bertugas overwatch saat skirmish.',
                ],
            ],
            [
                'callsign' => 'GHOST-08',
                'memberId' => 'MRT-007',
                'role' => ['en' => 'Member', 'id' => 'Anggota'],
                'statusKey' => 'active',
                'status' => ['en' => 'Active', 'id' => 'Aktif'],
                'joinDate' => '2025-05',
                'impressionEra' => ['en' => 'MultiCam (before 2025)', 'id' => 'MultiCam (sebelum 2025)'],
                'impressionFocus' => ['en' => 'MARSOC — Raider Element', 'id' => 'MARSOC — Elemen Raider'],
                'primaryPlatform' => 'SCAR Mk17',
                'secondaryPlatform' => 'Glock 17',
                'grid' => '01.40°S 120.10°E',
                'bio' => [
                    'en' => "Joined through a friend's squad and is still dialing in a MultiCam-era loadout.",
                    'id' => 'Bergabung lewat squad teman dan masih menyempurnakan loadout era MultiCam.',
                ],
            ],
            [
                'callsign' => 'INVICTUS-03',
                'memberId' => 'MRT-008',
                'role' => ['en' => 'Probationary Member', 'id' => 'Anggota Masa Percobaan'],
                'statusKey' => 'probationary',
                'status' => ['en' => 'Probationary', 'id' => 'Masa Percobaan'],
                'joinDate' => '2025-10',
                'impressionEra' => ['en' => 'Return to MARPAT (2025 →)', 'id' => 'Return to MARPAT (2025 →)'],
                'impressionFocus' => ['en' => 'Undeclared', 'id' => 'Belum ditentukan'],
                'primaryPlatform' => 'Mk18 Mod 1',
                'secondaryPlatform' => '—',
                'grid' => '00.89°S 119.87°E',
                'bio' => [
                    'en' => 'Newest grid reference — completing orientation before full membership review.',
                    'id' => 'Grid reference terbaru — masih menjalani orientasi sebelum tinjauan keanggotaan penuh.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findByCallsign(string $callsign): ?array
    {
        foreach (self::all() as $member) {
            if (strtolower((string) $member['callsign']) === strtolower($callsign)) {
                return $member;
            }
        }

        return null;
    }
}
