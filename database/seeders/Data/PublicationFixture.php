<?php

declare(strict_types=1);

namespace Database\Seeders\Data;

/**
 * Milestone-2 transcription of src/data/publications.js — throwaway once
 * the real Publication model + seeder exist (milestone 6).
 */
final class PublicationFixture
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            [
                'slug' => 'understanding-marsoc-brief-history',
                'title' => ['en' => 'Understanding MARSOC: A Brief History', 'id' => 'Memahami MARSOC: Sejarah Singkat'],
                'category' => ['en' => 'MARSOC History', 'id' => 'Sejarah MARSOC'],
                'author' => 'SPIRITUS-02',
                'date' => '2025-04-02',
                'excerpt' => [
                    'en' => 'How Marine Corps Forces Special Operations Command came to exist, and why the Raider name was revived for it.',
                    'id' => 'Bagaimana Marine Corps Forces Special Operations Command terbentuk, dan mengapa nama Raider dihidupkan kembali untuknya.',
                ],
                'body' => [
                    'en' => [
                        "U.S. Marine Corps Forces Special Operations Command (MARSOC) was activated in February 2006 as the Marine Corps' contribution to U.S. Special Operations Command — the newest of the service special-operations components.",
                        'In 2014–2015, MARSOC renamed its subordinate battalions to revive the Raider name carried by the Marine Raider battalions of World War II, organizing today around the Marine Raider Regiment, the Marine Raider Support Group, and the Marine Raider Training Center.',
                        'M.R.T borrows this lineage as an impression reference — a visual and ethos inspiration — not as a claim to any official status, training pipeline, or affiliation.',
                    ],
                    'id' => [
                        'U.S. Marine Corps Forces Special Operations Command (MARSOC) diaktifkan Februari 2006 sebagai kontribusi Marine Corps untuk U.S. Special Operations Command — komponen special-operations termuda di antara matra-matra AS.',
                        'Pada 2014–2015, MARSOC mengganti nama batalion-batalionnya untuk menghidupkan kembali nama Raider yang pernah disandang batalion Marine Raider era Perang Dunia II, kini terorganisasi di sekitar Marine Raider Regiment, Marine Raider Support Group, dan Marine Raider Training Center.',
                        'M.R.T meminjam garis sejarah ini sebagai referensi impresi — inspirasi visual dan ethos — bukan sebagai klaim status resmi, jalur pelatihan, atau afiliasi apa pun.',
                    ],
                ],
                'sources' => ['MARSOC Pub 1', 'Raider Creed', 'MARSOF 2030 / Cognitive Raider'],
            ],
            [
                'slug' => 'marine-raiders-equipment-reference',
                'title' => ['en' => 'Marine Raiders Equipment Reference: Building the Modern Loadout', 'id' => 'Referensi Equipment Marine Raiders: Membangun Loadout Modern'],
                'category' => ['en' => 'Equipment Reference', 'id' => 'Referensi Equipment'],
                'author' => 'TADULAKO-04',
                'date' => '2025-05-21',
                'excerpt' => [
                    'en' => 'A walkthrough of the weapon platforms, uniforms, and headgear M.R.T uses as reference points — and why context beats checklist.',
                    'id' => 'Panduan platform senjata, seragam, dan headgear yang dipakai M.R.T sebagai titik referensi — dan mengapa konteks mengalahkan checklist.',
                ],
                'body' => [
                    'en' => [
                        'A loadout reads as authentic when every piece answers to the same era and role, not when it carries the most expensive item in each slot.',
                        'Start from the Impression Reference Center: pick an era, confirm the uniform pattern it implies, then layer gear color, helmet type, and weapon platform on top — in that order, not reversed.',
                        'The equipment list in the reference center is a starting point for research, not a shopping list to complete item by item.',
                    ],
                    'id' => [
                        'Loadout terasa autentik ketika setiap bagiannya menjawab era dan role yang sama, bukan karena memakai barang termahal di setiap slot.',
                        'Mulai dari Impression Reference Center: pilih satu era, pastikan pola seragam yang berlaku, baru lapisi warna gear, jenis helm, dan platform senjata di atasnya — dengan urutan ini, bukan sebaliknya.',
                        'Daftar equipment di reference center adalah titik awal riset, bukan daftar belanja yang harus dicentang satu per satu.',
                    ],
                ],
                'sources' => ['Tier Three Tactical — Marine Raider Loadout', 'MARSOC — MMAC Individual Equipment List'],
            ],
            [
                'slug' => 'mk18-mod1-impression-guide',
                'title' => ['en' => 'Mk18 Mod 1 Impression Guide', 'id' => 'Panduan Impresi Mk18 Mod 1'],
                'category' => ['en' => 'Loadout Guide', 'id' => 'Panduan Loadout'],
                'author' => 'VANGUARD-07',
                'date' => '2025-06-14',
                'excerpt' => [
                    'en' => 'From Daniel Defense RIS II classic builds to the modern Geissele Mk16 URG-I style — picking a Mk18 configuration that matches your chosen era.',
                    'id' => 'Dari build klasik Daniel Defense RIS II sampai gaya modern Geissele Mk16 URG-I — memilih konfigurasi Mk18 yang sesuai era pilihanmu.',
                ],
                'body' => [
                    'en' => [
                        'The Mk18 Mod 1 spans two visually distinct eras: the classic Daniel Defense RIS II rail most associated with early MARSOC deployments, and the modern Geissele Mk16 9.3" "URG-I style" rail seen on current-issue builds.',
                        'Match the rail generation to the uniform era in the reference center — a modern URG-I upper over an early-MARPAT uniform reads as two different timelines stitched together.',
                    ],
                    'id' => [
                        'Mk18 Mod 1 membentang dua era yang berbeda secara visual: rail klasik Daniel Defense RIS II yang identik dengan deployment awal MARSOC, dan rail modern Geissele Mk16 9.3" "gaya URG-I" pada build era saat ini.',
                        'Cocokkan generasi rail dengan era seragam di reference center — upper URG-I modern di atas seragam early-MARPAT akan terbaca seperti dua garis waktu yang dijahit jadi satu.',
                    ],
                ],
                'sources' => ['Wikipedia — List of weapons of the United States Marine Corps'],
            ],
            [
                'slug' => 'evolution-of-marsoc-combat-uniforms',
                'title' => ['en' => 'Evolution of MARSOC Combat Uniforms', 'id' => 'Evolusi Seragam Tempur MARSOC'],
                'category' => ['en' => 'Historical Documentation', 'id' => 'Dokumentasi Historis'],
                'author' => 'SENTINEL-06',
                'date' => '2025-07-09',
                'excerpt' => [
                    'en' => 'MCCUU to FROG to Crye G3 to MultiCam and back to MARPAT — tracing the uniform eras members choose between.',
                    'id' => 'MCCUU ke FROG ke Crye G3 ke MultiCam dan kembali ke MARPAT — menelusuri era seragam yang jadi pilihan anggota.',
                ],
                'body' => [
                    'en' => [
                        'MARPAT replaced the legacy woodland/desert BDU pattern across the Marine Corps by 2004, and the standard MCCUU remains the base of every MARPAT-era impression.',
                        'MARSOC layered its own choices on top: flame-resistant FROG gear for deployment, a 2010 order of Crye G3 in Woodland for a visually distinct early-MARSOC look, and eventually MultiCam to match other SOF units.',
                        'That last choice was reversed by a MARSOC Commandant memo dated 20 September 2024, effective 1 January 2025, ending MultiCam in favor of a return to MARPAT with OD Green gear.',
                    ],
                    'id' => [
                        'MARPAT menggantikan pola BDU woodland/desert lama di seluruh Marine Corps pada 2004, dan MCCUU standar tetap jadi basis setiap impresi era MARPAT.',
                        'MARSOC menambahkan pilihannya sendiri: gear FROG tahan api untuk deployment, pemesanan Crye G3 Woodland pada 2010 untuk tampilan early-MARSOC yang khas, dan akhirnya MultiCam agar selaras dengan unit SOF lain.',
                        'Pilihan terakhir itu dibalik lewat memo Komandan MARSOC tertanggal 20 September 2024, efektif 1 Januari 2025, yang mengakhiri MultiCam dan kembali ke MARPAT dengan gear OD Green.',
                    ],
                ],
                'sources' => ['Wikipedia — MARPAT; Flame Resistant Organizational Gear', 'Military.com Kit Up!'],
            ],
            [
                'slug' => 'building-an-accurate-marine-raider-impression',
                'title' => ['en' => 'Building an Accurate Marine Raider Impression', 'id' => 'Membangun Impresi Marine Raider yang Akurat'],
                'category' => ['en' => 'Military Impression', 'id' => 'Military Impression'],
                'author' => 'RAIDER-01',
                'date' => '2025-08-03',
                'excerpt' => [
                    'en' => 'Context over checklist: why one consistent era beats a mix of expensive gear, and how M.R.T evaluates a new impression.',
                    'id' => 'Konteks di atas checklist: mengapa satu era yang konsisten mengalahkan campuran gear mahal, dan bagaimana M.R.T menilai impresi baru.',
                ],
                'body' => [
                    'en' => [
                        "A new member's first loadout review isn't a gear-value audit — it's a question of whether the pieces tell one consistent story.",
                        "We'd rather see a budget MultiCam-era setup built correctly than a MARPAT shirt paired with a 2025 OD Green carrier and a MultiCam helmet cover.",
                        'Section 10 of the identity book exists so that question has a documented answer before anyone spends money.',
                    ],
                    'id' => [
                        'Review loadout pertama anggota baru bukan audit nilai gear — melainkan pertanyaan apakah semua bagiannya bercerita satu kisah yang konsisten.',
                        'Kami lebih memilih setup era MultiCam murah yang benar daripada kemeja MARPAT dipasangkan carrier OD Green 2025 dan cover helm MultiCam.',
                        'Section 10 identity book ada agar pertanyaan itu punya jawaban terdokumentasi sebelum ada yang menghabiskan uang.',
                    ],
                ],
                'sources' => ['M.R.T Identity Book — Section 10'],
            ],
            [
                'slug' => 'grid-zero-after-action',
                'title' => ['en' => 'Grid Zero After-Action: Lessons from Our First Training Day', 'id' => 'After-Action Grid Zero: Pelajaran dari Hari Latihan Pertama Kami'],
                'category' => ['en' => 'Event Report', 'id' => 'Laporan Kegiatan'],
                'author' => 'ANOA-05',
                'date' => '2025-03-18',
                'excerpt' => [
                    'en' => 'What went right, what slowed the lane down, and what changed in our safety brief after Grid Zero.',
                    'id' => 'Apa yang berjalan baik, apa yang memperlambat lane, dan apa yang berubah di safety brief kami setelah Grid Zero.',
                ],
                'body' => [
                    'en' => [
                        'Grid Zero ran movement fundamentals and radio discipline ahead of anything scenario-based — eye-protection checks first, per the standing order in the identity book.',
                        "The biggest slowdown was comms: callsigns weren't consistently used on the first two runs. By the third run, discipline held and lane times improved noticeably.",
                        'The THINK → ADAPT → EXECUTE → LEARN cycle ran as a literal drill structure here, not just a motto — brief, rehearse, run the lane, debrief on camera.',
                    ],
                    'id' => [
                        'Grid Zero menjalankan dasar pergerakan dan disiplin radio sebelum apa pun yang berbasis skenario — cek eye protection didahulukan, sesuai aturan tetap di identity book.',
                        'Perlambatan terbesar ada di komunikasi: callsign belum dipakai konsisten di dua run pertama. Pada run ketiga, disiplin terjaga dan waktu tempuh lane membaik signifikan.',
                        'Siklus THINK → ADAPT → EXECUTE → LEARN benar-benar dijalankan sebagai struktur latihan di sini, bukan sekadar motto — briefing, gladi, jalankan lane, debrief dengan rekaman kamera.',
                    ],
                ],
                'sources' => ['Internal after-action notes'],
                'relatedActivity' => 'grid-zero-training-day',
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findBySlug(string $slug): ?array
    {
        foreach (self::all() as $publication) {
            if ($publication['slug'] === $slug) {
                return $publication;
            }
        }

        return null;
    }
}
