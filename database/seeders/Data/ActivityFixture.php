<?php

declare(strict_types=1);

namespace Database\Seeders\Data;

/**
 * Milestone-2 transcription of src/data/activities.js — throwaway once the
 * real Activity model + seeder exist (milestone 6).
 */
final class ActivityFixture
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            [
                'slug' => 'grid-zero-training-day',
                'title' => ['en' => 'Grid Zero: Foundational Training Day', 'id' => 'Grid Zero: Hari Latihan Dasar'],
                'category' => ['en' => 'Training Day', 'id' => 'Hari Latihan'],
                'date' => '2025-03-15',
                'location' => 'Sigi, Sulawesi Tengah',
                'grid' => '01.28°S 119.96°E',
                'participants' => 7,
                'relatedMembers' => ['RAIDER-01', 'SPIRITUS-02', 'ANOA-05'],
                'overview' => [
                    'en' => 'First full muster of the year. Movement fundamentals, radio discipline, and eye-protection checks before anything else — the standing order from section 10.4 of the identity book.',
                    'id' => 'Muster penuh pertama tahun ini. Dasar pergerakan, disiplin radio, dan cek eye protection didahulukan — sesuai aturan tetap di section 10.4 identity book.',
                ],
                'notes' => [
                    'en' => 'THINK → ADAPT → EXECUTE → LEARN ran as a literal drill cycle: brief, rehearse, run the lane, debrief on camera.',
                    'id' => 'THINK → ADAPT → EXECUTE → LEARN dijalankan sebagai siklus latihan nyata: briefing, gladi, jalankan lane, debrief dengan rekaman kamera.',
                ],
            ],
            [
                'slug' => 'operation-anoa-shield',
                'title' => ['en' => 'Operation Anoa Shield', 'id' => 'Operation Anoa Shield'],
                'category' => ['en' => 'MilSim Event', 'id' => 'Kegiatan MilSim'],
                'date' => '2025-05-10',
                'location' => 'Kulawi, Sulawesi Tengah',
                'grid' => '01.40°S 120.10°E',
                'participants' => 14,
                'relatedMembers' => ['RAIDER-01', 'TADULAKO-04', 'SENTINEL-06', 'VANGUARD-07'],
                'overview' => [
                    'en' => 'Full-day milsim scenario built around a defend-and-hold objective. M.R.T fielded a Raider element alongside two allied impression teams under a shared marshal staff.',
                    'id' => 'Skenario milsim sehari penuh dengan objektif defend-and-hold. M.R.T menurunkan elemen Raider bersama dua tim impresi sekutu di bawah satu marshal staff.',
                ],
                'notes' => [
                    'en' => 'First outing where the full THINK/ADAPT/EXECUTE/LEARN cycle was run as a formal after-action review with the whole element present.',
                    'id' => 'Kegiatan pertama dengan siklus penuh THINK/ADAPT/EXECUTE/LEARN dijalankan sebagai after-action review resmi dengan seluruh elemen hadir.',
                ],
            ],
            [
                'slug' => 'tadulako-cup-skirmish-3',
                'title' => ['en' => 'Tadulako Cup Skirmish Series — Round 3', 'id' => 'Tadulako Cup Skirmish Series — Ronde 3'],
                'category' => ['en' => 'Airsoft Skirmish', 'id' => 'Skirmish Airsoft'],
                'date' => '2025-07-20',
                'location' => 'Palu, Sulawesi Tengah',
                'grid' => '00.89°S 119.87°E',
                'participants' => 22,
                'relatedMembers' => ['GHOST-08', 'VANGUARD-07'],
                'overview' => [
                    'en' => 'Open skirmish round hosted with two other local communities. Standard CQB ruleset, mixed loadouts, open to non-members as a recruiting touchpoint.',
                    'id' => 'Skirmish terbuka bersama dua komunitas lokal lain. Ruleset CQB standar, loadout bebas, terbuka untuk non-anggota sebagai ajang perekrutan.',
                ],
                'notes' => [
                    'en' => 'Good stress-test for comms discipline under a louder, more chaotic field than training days.',
                    'id' => 'Stress-test yang bagus untuk disiplin komunikasi di lapangan yang lebih ramai dan kacau dibanding hari latihan biasa.',
                ],
            ],
            [
                'slug' => 'joint-field-exercise',
                'title' => ['en' => 'Joint Field Exercise with Allied Impression Units', 'id' => 'Latihan Gabungan dengan Unit Impresi Sekutu'],
                'category' => ['en' => 'Joint Operation', 'id' => 'Operasi Gabungan'],
                'date' => '2025-08-30',
                'location' => 'Sigi, Sulawesi Tengah',
                'grid' => '01.28°S 119.96°E',
                'participants' => 31,
                'relatedMembers' => ['RAIDER-01', 'SPIRITUS-02', 'TADULAKO-04'],
                'overview' => [
                    'en' => 'Multi-community field day coordinating movement and comms across three independent impression groups for a single scenario.',
                    'id' => 'Field day lintas komunitas yang mengoordinasikan pergerakan dan komunikasi tiga grup impresi independen untuk satu skenario.',
                ],
                'notes' => [
                    'en' => 'Representing the community outside its own roster — RESPECT and REPRESENT standards enforced strictly.',
                    'id' => 'Mewakili komunitas di luar rosternya sendiri — standar RESPECT dan REPRESENT ditegakkan ketat.',
                ],
            ],
            [
                'slug' => 'patch-and-brotherhood-night',
                'title' => ['en' => 'Patch & Brotherhood Night', 'id' => 'Malam Patch & Brotherhood'],
                'category' => ['en' => 'Community Gathering', 'id' => 'Kumpul Komunitas'],
                'date' => '2025-09-12',
                'location' => 'Palu, Sulawesi Tengah',
                'grid' => '00.89°S 119.87°E',
                'participants' => 9,
                'relatedMembers' => ['RAIDER-01', 'ANOA-05', 'GHOST-08', 'INVICTUS-03'],
                'overview' => [
                    'en' => 'Off-field gathering — patch presentations for new members, loadout show-and-tell, no game. The social half of the standards sheet.',
                    'id' => 'Kumpul di luar lapangan — presentasi patch untuk anggota baru, show-and-tell loadout, tanpa game. Sisi sosial dari lembar standar.',
                ],
                'notes' => [
                    'en' => 'INVICTUS-03 presented for probationary review at this gathering.',
                    'id' => 'INVICTUS-03 tampil untuk tinjauan masa percobaan pada kumpul ini.',
                ],
            ],
            [
                'slug' => 'return-to-marpat-clinic',
                'title' => ['en' => 'Loadout Clinic: Return-to-MARPAT Build Night', 'id' => 'Klinik Loadout: Malam Build Return-to-MARPAT'],
                'category' => ['en' => 'Equipment Showcase', 'id' => 'Showcase Equipment'],
                'date' => '2025-10-18',
                'location' => 'Palu, Sulawesi Tengah',
                'grid' => '00.89°S 119.87°E',
                'participants' => 6,
                'relatedMembers' => ['TADULAKO-04', 'VANGUARD-07', 'SENTINEL-06'],
                'overview' => [
                    'en' => 'Working session walking members through rebuilding MultiCam-era loadouts to OD Green per the September 2024 MARSOC memo, section 10 of the reference library.',
                    'id' => 'Sesi kerja yang memandu anggota membangun ulang loadout era MultiCam menjadi OD Green sesuai memo MARSOC September 2024, section 10 reference library.',
                ],
                'notes' => [
                    'en' => 'Reference-first: every substitution cross-checked against the identity book before anyone bought gear.',
                    'id' => 'Referensi dulu, baru beli: setiap substitusi dicek silang dengan identity book sebelum ada yang belanja gear.',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findBySlug(string $slug): ?array
    {
        foreach (self::all() as $activity) {
            if ($activity['slug'] === $slug) {
                return $activity;
            }
        }

        return null;
    }
}
