<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Database\Seeder;

/**
 * Transcribes the ~28 page_blocks from the frontend's original i18n
 * content (lang/{id,en}.json — the content half, not the UI-chrome half
 * that stays in those files) plus the 11-line creed, which previously
 * lived hardcoded in Home.vue. This is the one-time migration that makes
 * every prose block, repeating card list, and the creed admin-editable.
 *
 * Idempotent via updateOrCreate on the natural key (block_key) — no
 * transactions on standalone Mongo, so a seeder that dies partway must
 * be safely re-runnable.
 */
final class PageBlockSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['home', 'about', 'personnel', 'impression', 'activities', 'media', 'publications', 'join'] as $sort => $key) {
            Page::query()->updateOrCreate(['key' => $key], ['sort' => $sort * 10, 'is_published' => true]);
        }

        $blocks = [
            // ---------- Page heroes ----------
            ['page_key' => 'home', 'block_key' => 'home.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Komunitas Airsoft Impression · Sulawesi Tengah', 'Airsoft Impression Community · Sulawesi Tengah'), 'heading' => self::t('M.R.T — Marine Raider Tadulako — komunitas airsoft impression di Sulawesi Tengah', 'M.R.T — Marine Raider Tadulako — an airsoft impression community in Sulawesi Tengah'), 'subheading' => self::t('Komunitas military impression asal Indonesia yang terinspirasi oleh sejarah, tradisi, dan warisan United States Marine Forces Special Operations Command (MARSOC) — dibangun di atas tanah Sulawesi Tengah sendiri.', "An Indonesian military impression community inspired by the history, traditions, and legacy of the United States Marine Forces Special Operations Command (MARSOC) — built on Sulawesi Tengah's own ground.")],
            ['page_key' => 'about', 'block_key' => 'about.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('About — identitas komunitas', 'About — community identity'), 'heading' => self::t('Kisah Kami, Posisi Kami', 'Our Story, Our Standing'), 'subheading' => self::t('Mengapa M.R.T ada, apa yang kami perjuangkan, dan di mana batas antara impresi dan klaim.', 'Why M.R.T exists, what it stands for, and where the line sits between impression and claim.')],
            ['page_key' => 'personnel', 'block_key' => 'personnel.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Personnel — catatan dinas', 'Personnel — service records'), 'heading' => self::t('Roster', 'Roster'), 'subheading' => self::t('Setiap anggota M.R.T memiliki grid reference, callsign, dan fokus impresi yang dideklarasikan. Data pribadi dipublikasikan hanya dengan persetujuan masing-masing anggota.', "Every M.R.T member carries a grid reference, a callsign, and a declared impression focus. Personal data is published only with each member's consent.")],
            ['page_key' => 'impression', 'block_key' => 'impression.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Impression — pusat referensi MARSOC', 'Impression — MARSOC reference center'), 'heading' => self::t('Referensi, Bukan Keharusan', 'Reference, Not Requirement'), 'subheading' => self::t('Sejarah, era seragam, platform senjata, dan referensi loadout yang dipakai anggota untuk membangun impresi MARSOC / Marine Raider yang akurat — konteks di atas checklist.', 'History, uniform eras, weapon platforms, and loadout references members use to build an accurate MARSOC / Marine Raider impression — context over checklist.')],
            ['page_key' => 'activities', 'block_key' => 'activities.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Activities — catatan kegiatan', 'Activities — field log'), 'heading' => self::t('Training, Skirmish, Milsim', 'Training, Skirmish, Milsim'), 'subheading' => self::t('Kegiatan komunitas airsoft dan milsim sipil M.R.T — latihan, skirmish, dan acara bersama.', 'Civilian airsoft and milsim community activity from M.R.T — training days, skirmishes, and joint events.')],
            ['page_key' => 'media', 'block_key' => 'media.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Media Archive — dokumentasi', 'Media Archive — documentation'), 'heading' => self::t('Dokumentasi Lapangan & Referensi', 'Field & Reference Imagery'), 'subheading' => self::t('Dokumentasi kegiatan, tim, dan referensi equipment M.R.T. Foto referensi equipment adalah foto real-steel sebagai acuan impresi — anggota M.R.T hanya memakai replika airsoft.', 'Documentation of M.R.T activities, teams, and equipment references. Equipment reference photos are real-steel images used as impression guides — M.R.T members field airsoft replicas only.')],
            ['page_key' => 'publications', 'block_key' => 'publications.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Publications — editorial', 'Publications — editorial'), 'heading' => self::t('Artikel & Catatan Lapangan', 'Articles & Field Notes'), 'subheading' => self::t('Tulisan sejarah, referensi equipment, panduan loadout, dan laporan kegiatan dari komunitas.', 'History write-ups, equipment references, loadout guides, and event reports from the community.')],
            ['page_key' => 'join', 'block_key' => 'join.hero', 'layout' => 'page_hero', 'sort' => 10, 'eyebrow' => self::t('Join Us — keanggotaan & rekrutmen', 'Join Us — membership & recruitment'), 'heading' => self::t('Report for Duty', 'Report for Duty'), 'subheading' => self::t('Baca dulu syarat dan standar kami. Formulir di bawah akan menyusun pesan langsung ke WhatsApp kami — tidak ada antrean formulir tanpa wajah di sini.', 'Read the eligibility and standards first. The application below drafts a message straight to our WhatsApp — there is no faceless form queue here.')],

            // ---------- Home ----------
            [
                'page_key' => 'home', 'block_key' => 'home.formula', 'layout' => 'card_grid', 'sort' => 20,
                'heading' => self::t('Formula Identitas M.R.T', 'The M.R.T Identity Formula'),
                'footnote' => self::t('Kami tidak sekadar memakai impresi. Kami memahaminya, mewakilinya, dan membangunnya bersama.', 'We do not simply wear the impression. We understand it, represent it, and build it together.'),
                'items' => [
                    ['key' => 'marsoc', 'label' => 'MARSOC', 'meta' => self::t('Inspirasi', 'Inspiration'), 'body' => self::t('Referensi visual dan ethos untuk impresi — bukan status atau afiliasi resmi.', 'Visual and ethos reference for the impression — never status or official affiliation.'), 'sort' => 10],
                    ['key' => 'tadulako', 'label' => 'TADULAKO', 'meta' => self::t('Akar', 'Roots'), 'body' => self::t('Identitas Sulawesi Tengah yang tidak bisa diklaim unit luar mana pun.', 'Sulawesi Tengah identity no outside unit can claim.'), 'sort' => 20],
                    ['key' => 'raider', 'label' => 'RAIDER', 'meta' => self::t('Ethos', 'Ethos'), 'body' => self::t('Determination, dependability, teamwork, quiet professionalism.', 'Determination, dependability, teamwork, quiet professionalism.'), 'sort' => 30],
                    ['key' => 'mrt', 'label' => 'M.R.T', 'meta' => self::t('Komunitas', 'Community'), 'body' => self::t('Call sign kita — Marine Raider Tadulako.', 'Our call sign — Marine Raider Tadulako.'), 'sort' => 40],
                ],
            ],
            [
                'page_key' => 'home', 'block_key' => 'home.standards', 'layout' => 'numbered_list', 'sort' => 30,
                'heading' => self::t('Lima Standar', 'Five Standards'),
                'subheading' => self::t('Lima bintang pada lambang. Lima standar yang dijaga di dalam maupun di luar lapangan.', 'Five stars on the insignia. Five standards held on and off the field.'),
                'items' => [
                    ['key' => 'courage', 'value' => '01', 'label' => 'Courage', 'body' => self::t('Memilih untuk bertindak saat dibutuhkan.', 'Choosing to act when it matters.'), 'sort' => 10],
                    ['key' => 'discipline', 'value' => '02', 'label' => 'Discipline', 'body' => self::t('Standar yang tetap dijaga walau tak ada yang mengawasi.', 'Standards held even when no one is watching.'), 'sort' => 20],
                    ['key' => 'brotherhood', 'value' => '03', 'label' => 'Brotherhood', 'body' => self::t('Tidak ada yang menanggung sendirian.', 'No one carries it alone.'), 'sort' => 30],
                    ['key' => 'resilience', 'value' => '04', 'label' => 'Resilience', 'body' => self::t('Bertahan, beradaptasi, terus bergerak maju.', 'Endure, adapt, keep moving forward.'), 'sort' => 40],
                    ['key' => 'honor', 'value' => '05', 'label' => 'Honor', 'body' => self::t('Reputasi didapat lewat tindakan, bukan diberikan begitu saja.', 'Reputation is earned, never issued.'), 'sort' => 50],
                ],
            ],
            [
                'page_key' => 'home', 'block_key' => 'home.creed', 'layout' => 'creed', 'sort' => 40,
                'footnote' => self::t('"Patch dapat dilepas. Reputasi tidak."', '"Patch dapat dilepas. Reputasi tidak."'),
                'items' => [
                    ['key' => 'l01', 'label' => 'WE ARE ROOTED IN TADULAKO.', 'value' => 'md', 'sort' => 10],
                    ['key' => 'l02', 'label' => 'WE ARE INSPIRED BY THE RAIDERS.', 'value' => 'md', 'sort' => 20],
                    ['key' => 'l03', 'label' => 'WE CARRY THE SPIRIT OF THE ANOA.', 'value' => 'md', 'sort' => 30],
                    ['key' => 'l04', 'label' => 'WE CHOOSE COURAGE.', 'value' => 'lg', 'sort' => 40],
                    ['key' => 'l05', 'label' => 'WE PRACTICE DISCIPLINE.', 'value' => 'lg', 'sort' => 50],
                    ['key' => 'l06', 'label' => 'WE STAND IN BROTHERHOOD.', 'value' => 'lg', 'sort' => 60],
                    ['key' => 'l07', 'label' => 'WE ENDURE THROUGH RESILIENCE.', 'value' => 'lg', 'sort' => 70],
                    ['key' => 'l08', 'label' => 'WE UPHOLD HONOR.', 'value' => 'lg', 'sort' => 80],
                    ['key' => 'l09', 'label' => 'WE ADAPT.', 'value' => 'xl', 'sort' => 90],
                    ['key' => 'l10', 'label' => 'WE ENDURE.', 'value' => 'xl', 'sort' => 100],
                    ['key' => 'l11', 'label' => 'WE MOVE FORWARD.', 'value' => 'xl', 'sort' => 110],
                ],
            ],
            ['page_key' => 'home', 'block_key' => 'home.latest-activities', 'layout' => 'feed_teaser', 'sort' => 50, 'heading' => self::t('Kegiatan Terbaru', 'Latest Activities'), 'footnote' => self::t('Catatan kegiatan komunitas M.R.T, diurutkan dari yang terbaru.', 'M.R.T community field log, newest first.')],
            ['page_key' => 'home', 'block_key' => 'home.impression-teaser', 'layout' => 'feed_teaser', 'sort' => 60, 'heading' => self::t('Impression Reference Center', 'Impression Reference Center'), 'subheading' => self::t('Era seragam, platform senjata, headgear, dan referensi loadout — bersumber, bertanggal, dan dibangun atas dasar konteks, bukan sekadar daftar belanja.', 'Uniform eras, weapon platforms, headgear, and loadout references — sourced, dated, and built for context over checklist.')],
            ['page_key' => 'home', 'block_key' => 'home.latest-media', 'layout' => 'feed_teaser', 'sort' => 70, 'heading' => self::t('Dokumentasi Terbaru', 'Latest Documentation')],
            ['page_key' => 'home', 'block_key' => 'home.final-cta', 'layout' => 'feed_teaser', 'sort' => 80, 'heading' => self::t('Siap gabung barisan?', 'Ready to report in?'), 'subheading' => self::t('Pahami dulu standar dan identitas kami, lalu hubungi kami. Kami lebih suka mengobrol dulu dengan kamu daripada bertemu kamu di lapangan tanpa persiapan.', "Read the standards, understand the identity, then reach out. We'd rather talk to you first than see you on the field unprepared.")],

            // ---------- About ----------
            ['page_key' => 'about', 'block_key' => 'about.story', 'layout' => 'prose', 'sort' => 20, 'heading' => self::t('Kisah Kami', 'Our Story'), 'body' => self::t('M.R.T — Marine Raider Tadulako — dimulai dari segelintir pemain airsoft Sulawesi Tengah yang ingin impresi MARSOC yang dibangun lewat riset, bukan sekadar tampilan. Alih-alih meniru satu unit secara utuh, grup ini menambatkan identitasnya pada Tadulako, memberi impresi ini akar lokal yang tak bisa diklaim unit luar mana pun. Identity book yang mengatur logo, standar, dan creed di situs ini adalah dokumen yang sama yang ditulis para pendiri untuk mengikat diri mereka pada standar tersebut.', 'M.R.T — Marine Raider Tadulako — started as a handful of Sulawesi Tengah airsoft players who wanted a MARSOC impression built on research rather than looks alone. Rather than copy an existing unit wholesale, the group anchored its identity to Tadulako, giving the impression a local root no outside unit could claim. The identity book that governs this site\'s logo, standards, and creed is the same document the founding members wrote to hold themselves to that standard.')],
            [
                'page_key' => 'about', 'block_key' => 'about.mission-vision', 'layout' => 'definition_list', 'sort' => 30,
                'heading' => self::t('Misi & Visi', 'Mission & Vision'),
                'items' => [
                    ['key' => 'mission', 'label' => self::t('Misi', 'Mission'), 'body' => self::t('Memberi pemain airsoft Sulawesi Tengah komunitas impresi MARSOC/Marine Raider yang disiplin dan referensinya terverifikasi — di mana setiap pilihan konfigurasi punya konteks, bukan sekadar struk belanja.', 'Give Sulawesi Tengah airsoft players a disciplined, source-checked MARSOC/Marine Raider impression community — one where configuration choices have context, not just a receipt.'), 'sort' => 10],
                    ['key' => 'vision', 'label' => self::t('Visi', 'Vision'), 'body' => self::t('Komunitas yang dijadikan rujukan oleh grup impresi lain soal cara mendokumentasikan impresi dengan benar: sejarah bersumber, era yang konsisten, dan batas yang jelas antara inspirasi dan klaim.', 'A community that other impression groups point to for how to document an impression properly: sourced history, consistent eras, and a visible line between inspiration and claim.'), 'sort' => 20],
                ],
            ],
            [
                'page_key' => 'about', 'block_key' => 'about.values', 'layout' => 'card_grid', 'sort' => 40,
                'heading' => self::t('Nilai-Nilai Kami', 'Our Values'),
                'items' => [
                    ['key' => 'honor', 'label' => 'Honor', 'body' => self::t('Reputasi didapat lewat perilaku, di dalam maupun di luar lapangan.', 'Reputation is earned through conduct, on and off the field.'), 'sort' => 10],
                    ['key' => 'discipline', 'label' => 'Discipline', 'body' => self::t('Safety, gear, dan komunikasi tetap sesuai standar bahkan saat permainan memanas.', 'Safety, gear, and communication held to standard even when the game heats up.'), 'sort' => 20],
                    ['key' => 'brotherhood', 'label' => 'Brotherhood', 'body' => self::t('Keberhasilan tim lebih penting daripada sorotan individu.', 'Team success outranks individual highlight reels.'), 'sort' => 30],
                    ['key' => 'respect', 'label' => 'Respect', 'body' => self::t('Rekan satu tim, lawan, marshal, venue, dan komunitas lain.', 'Teammates, opponents, marshals, venues, and other communities.'), 'sort' => 40],
                    ['key' => 'commitment', 'label' => 'Commitment', 'body' => self::t('Hadir untuk berkontribusi, bukan sekadar memakai gear.', 'Showing up to contribute, not just to wear gear.'), 'sort' => 50],
                    ['key' => 'authenticity', 'label' => 'Authenticity', 'body' => self::t('Riset dulu era dan role-nya sebelum membeli tampilannya.', 'Research the era and the role before buying the look.'), 'sort' => 60],
                ],
            ],
            ['page_key' => 'about', 'block_key' => 'about.identity', 'layout' => 'prose', 'sort' => 50, 'heading' => self::t('Identitas', 'Identity'), 'body' => self::t('Lambang ini terdiri dari Anoa, diamond, dan lima bintang. Tengkorak Anoa menandai akar Sulawesi, keberanian, dan ketahanan. Diamond mengikat individu menjadi satu identitas, bergerak sebagai tim. Lima bintang mewakili Lima Standar: Courage, Discipline, Brotherhood, Resilience, Honor.', 'The insignia reads Anoa, diamond, and five stars. The Anoa skull marks Sulawesi roots, courage, and resilience. The diamond binds individuals into one identity, moving as a team. The five stars stand for the Five Standards: Courage, Discipline, Brotherhood, Resilience, Honor.'), 'footnote' => self::t('Biru master adalah identitas resmi. Dark dan mono adalah turunan untuk patch dan gear — tidak pernah menggantikan. Proporsi, susunan bintang, diamond, dan bentuk Anoa tidak diubah.', 'Master blue is the identity of record. Dark and mono are derivatives for patches and gear — never a replacement. Proportions, star arrangement, the diamond, and the Anoa shape are not altered.')],
            [
                'page_key' => 'about', 'block_key' => 'about.logo-modes', 'layout' => 'logo_modes', 'sort' => 60,
                'items' => [
                    ['key' => 'master', 'label' => 'Master / Color', 'value' => 'logo_color', 'body' => self::t('Media resmi, dokumen, banner, dan branding utama.', 'Official media, documents, banners, and primary branding.'), 'sort' => 10],
                    ['key' => 'dark', 'label' => 'Dark / Subdued', 'value' => 'logo_gold_on_black', 'body' => self::t('Patch low-profile, gear, dan apparel.', 'Low-profile patches, gear, and apparel.'), 'sort' => 20],
                    ['key' => 'mono', 'label' => 'Mono / Low-Vis', 'value' => 'logo_white_on_black', 'body' => self::t('Bordir satu warna, stensil, dan reproduksi sederhana.', 'Single-color embroidery, stencils, and simple reproduction.'), 'sort' => 30],
                ],
            ],

            // ---------- Impression ----------
            ['page_key' => 'impression', 'block_key' => 'impression.history', 'layout' => 'prose', 'sort' => 20, 'heading' => self::t('Sejarah & Organisasi MARSOC', 'MARSOC History & Organization'), 'body' => self::t('U.S. Marine Corps Forces Special Operations Command (MARSOC) diaktifkan Februari 2006 sebagai kontribusi Marine Corps untuk U.S. Special Operations Command. Pada 2014–2015, unit-unit di bawah MARSOC diganti nama untuk menghidupkan kembali nama Raider dari batalion Marine Raider era Perang Dunia II — kini terorganisasi di sekitar Marine Raider Regiment, Marine Raider Support Group, dan Marine Raider Training Center. M.R.T meminjam garis sejarah ini sebagai inspirasi impresi, bukan sebagai klaim status resmi.', "U.S. Marine Corps Forces Special Operations Command (MARSOC) was activated in February 2006 as the Marine Corps' contribution to U.S. Special Operations Command. In 2014–2015, MARSOC's subordinate units were renamed to revive the Raider name from the Marine Raider battalions of World War II — today organized around the Marine Raider Regiment, the Marine Raider Support Group, and the Marine Raider Training Center. M.R.T borrows this lineage as inspiration for an impression, not as a claim of official status.")],
            ['page_key' => 'impression', 'block_key' => 'impression.eras', 'layout' => 'feed_teaser', 'sort' => 30, 'heading' => self::t('Era Seragam & Equipment', 'Uniform & Equipment Eras'), 'subheading' => self::t('Pilih satu era dan bangun secara konsisten ke arah itu — loadout campuran era terbaca lebih lemah dibanding loadout murah yang konsisten.', 'Pick one era and build toward it consistently — a mixed-era loadout reads weaker than a cheaper, consistent one.')],
            ['page_key' => 'impression', 'block_key' => 'impression.weapons', 'layout' => 'feed_teaser', 'sort' => 40, 'heading' => self::t('Weapon Platforms', 'Weapon Platforms'), 'subheading' => self::t('Platform referensi yang diriset untuk impresi M.R.T. Foto di bawah adalah foto referensi real-steel — setiap anggota M.R.T hanya memakai replika airsoft.', 'Reference platforms researched for M.R.T impressions. Photos below are real-steel reference images — every M.R.T member fields an airsoft replica only.')],
            [
                'page_key' => 'impression', 'block_key' => 'impression.headgear', 'layout' => 'definition_list', 'sort' => 50,
                'heading' => self::t('Headgear & NVG', 'Headgear & NVG'),
                'items' => [
                    ['key' => 'helmet', 'label' => 'Helmet', 'body' => self::t('Ops-Core FAST High Cut (kontrak SOCOM, 2019) atau bump base non-balistik — titik awal replika Airsoft yang umum. ECH/LWH/MICH mid-cut untuk impresi training/Marine umum.', 'Ops-Core FAST High Cut (SOCOM contract, 2019) or a non-ballistic bump base — the common Airsoft replica starting point. ECH/LWH/MICH mid-cut for training/Marine-general impressions.'), 'sort' => 10],
                    ['key' => 'headset', 'label' => 'Headset', 'body' => self::t('3M Peltor ComTac atau TCI Liberator over-ear, terpasang di rail — detail siluet Raider yang paling dikenali.', '3M Peltor ComTac or TCI Liberator over-ear, mounted on the rail — the most recognizable Raider silhouette detail.'), 'sort' => 20],
                    ['key' => 'nvg-mount', 'label' => 'NVG Mount', 'body' => self::t('Shroud tiga lubang dengan lengan Wilcox L4 G24 atau Norotos. Dummy PVS-14/15/31 sudah cukup untuk visual; tidak perlu NVG asli.', 'Three-hole shroud with a Wilcox L4 G24 or Norotos arm. Dummy PVS-14/15/31 is enough for the visual; no real NVG required.'), 'sort' => 30],
                    ['key' => 'patch-placement', 'label' => 'Patch Placement', 'body' => self::t('Patch M.R.T di lengan, panel depan carrier, dan sisi/belakang helm untuk IR patch dan callsign tape.', 'M.R.T patch on the sleeve, carrier front panel, and helmet side/rear for IR patch and callsign tape.'), 'sort' => 40],
                ],
            ],
            [
                'page_key' => 'impression', 'block_key' => 'impression.eye-protection', 'layout' => 'definition_list', 'sort' => 60,
                'heading' => self::t('Eye Protection', 'Eye Protection'),
                'items' => [
                    ['key' => 'standard', 'label' => 'Standard', 'body' => self::t('Militer AS memakai APEL (Authorized Protective Eyewear List) — kacamata dan goggle teruji standar ANSI Z87 + MIL-PRF-31013, berlaku sejak 2006.', 'The US military uses APEL (Authorized Protective Eyewear List) — tested glasses and goggles meeting ANSI Z87 + MIL-PRF-31013, in place since 2006.'), 'sort' => 10],
                    ['key' => 'approved-brands', 'label' => 'Approved Brands', 'body' => self::t('ESS, Wiley X, Revision, Oakley SI.', 'ESS, Wiley X, Revision, Oakley SI.'), 'sort' => 20],
                    ['key' => 'airsoft-rule', 'label' => 'Airsoft Rule', 'body' => self::t('Pakai produk asli ber-rating impact (tanda Z87+). Hindari replika yang cuma mirip tampilannya.', 'Use genuine impact-rated product (Z87+ marking). Avoid replicas that only look the part.'), 'sort' => 30],
                    ['key' => 'gatorz-magnum', 'label' => 'Gatorz Magnum', 'body' => self::t('Sunglass frame aluminium yang populer di kalangan Raiders dan SOF. Pakai varian Magnum Z / ANSI (tanda Z87+) dengan lensa bawaan pabrik — versi non-ANSI dan replika tidak aman untuk Airsoft.', 'An aluminum sunglass frame popular among Raiders and SOF. Use the Magnum Z / ANSI variant (Z87+ marking) with factory lenses — non-ANSI versions and replicas are not safe for Airsoft.'), 'sort' => 40],
                    ['key' => 'venue-rule', 'label' => 'Venue Rule', 'body' => self::t('Ikuti aturan venue dan marshal, termasuk perlindungan wajah bagian bawah bila diminta.', 'Follow venue and marshal rules, including lower-face protection when required.'), 'sort' => 50],
                ],
            ],
            [
                'page_key' => 'impression', 'block_key' => 'impression.library', 'layout' => 'source_list', 'sort' => 70,
                'heading' => self::t('Reference Library', 'Reference Library'),
                'subheading' => self::t('Sumber yang dirujuk saat menyusun reference center ini:', 'Sources consulted while assembling this reference center:'),
                'footnote' => self::t('Bedakan fakta historis (dikutip di atas) dari konfigurasi komunitas M.R.T sendiri dan replika airsoft yang benar-benar dipakai anggota.', "Distinguish historical fact (cited above) from M.R.T's own community configuration and the airsoft replicas members actually field."),
                'items' => [
                    ['key' => 's1', 'label' => 'Tier Three Tactical — Marine Raider Loadout', 'sort' => 10],
                    ['key' => 's2', 'label' => 'MARSOC — MMAC Individual Equipment List', 'sort' => 20],
                    ['key' => 's3', 'label' => 'Wikipedia — List of weapons of the United States Marine Corps; MARPAT; Flame Resistant Organizational Gear', 'sort' => 30],
                    ['key' => 's4', 'label' => 'Military.com Kit Up! / Soldier Systems Daily — MARSOC uniform orders and the 2024 MultiCam memo', 'sort' => 40],
                    ['key' => 's5', 'label' => 'Marine Corps Times — SOCOM helmet and headset contracts (2019)', 'sort' => 50],
                    ['key' => 's6', 'label' => 'Wikipedia — Authorized Protective Eyewear List', 'sort' => 60],
                    ['key' => 's7', 'label' => 'Gatorz Eyewear — Magnum ANSI Z87.1+ specifications', 'sort' => 70],
                ],
            ],

            // ---------- Join ----------
            ['page_key' => 'join', 'block_key' => 'join.about', 'layout' => 'prose', 'sort' => 20, 'heading' => self::t('Tentang Keanggotaan', 'About Membership'), 'body' => self::t('M.R.T adalah grup independen — bergabung tidak mengharuskanmu meninggalkan klub atau komunitas lain, dan keanggotaan di sini tidak mengubah afiliasi lain yang kamu miliki.', 'M.R.T is an independent group — joining does not require leaving another club or community, and membership here does not change any other affiliation you hold.')],
            [
                'page_key' => 'join', 'block_key' => 'join.eligibility', 'layout' => 'bullet_list', 'sort' => 30,
                'heading' => self::t('Syarat Keanggotaan', 'Eligibility'),
                'items' => [
                    ['key' => 'e1', 'body' => self::t('Cukup umur untuk memegang equipment sendiri dan mengikuti aturan safety venue secara mandiri.', 'Old enough to hold your own equipment and follow venue safety rules independently.'), 'sort' => 10],
                    ['key' => 'e2', 'body' => self::t('Bisa berkomitmen setidaknya di hari-hari latihan tertentu — ini komunitas, bukan layanan drop-in.', 'Able to commit to at least occasional training days — this is a community, not a drop-in service.'), 'sort' => 20],
                    ['key' => 'e3', 'body' => self::t('Bersedia mengikuti standar M.R.T dan aturan safety venue/marshal tanpa pengecualian.', "Willing to follow M.R.T's standards and venue/marshal safety rules without exception."), 'sort' => 30],
                ],
            ],
            ['page_key' => 'join', 'block_key' => 'join.expectations', 'layout' => 'prose', 'sort' => 40, 'heading' => self::t('Impression Expectations', 'Impression Expectations'), 'body' => self::t('Kamu tidak perlu loadout lengkap untuk mendaftar. Yang kamu perlukan adalah kesediaan memilih satu era dari Impression Reference Center dan membangun ke arah itu secara konsisten, bukan mencampur apa pun yang terlihat bagus.', "You don't need a complete loadout to apply. You do need willingness to pick one era from the Impression Reference Center and build toward it consistently, rather than mixing whatever looks good.")],
            [
                'page_key' => 'join', 'block_key' => 'join.rules', 'layout' => 'bullet_list', 'sort' => 50,
                'heading' => self::t('Peraturan Komunitas', 'Community Rules'),
                'items' => [
                    ['key' => 'r1', 'body' => self::t('Eye protection berstandar (ANSI Z87+ atau setara) wajib — tanpa pengecualian, tanpa replika sebagai pengganti.', 'Rated eye protection (ANSI Z87+ or equivalent) is mandatory — no exceptions, no replicas standing in for it.'), 'sort' => 10],
                    ['key' => 'r2', 'body' => self::t('Ikuti instruksi venue dan marshal tanpa membantah di lapangan.', 'Follow venue and marshal instructions without argument on the field.'), 'sort' => 20],
                    ['key' => 'r3', 'body' => self::t('Keberhasilan tim lebih penting daripada sorotan individu — tidak ada "one man show."', 'Team success outranks individual highlight reels — no "one man show."'), 'sort' => 30],
                    ['key' => 'r4', 'body' => self::t('Bawa nama M.R.T dengan bertanggung jawab di dalam maupun di luar lapangan; identitas ini adalah reputasi bersama.', 'Represent M.R.T responsibly on and off the field; the identity is a shared reputation.'), 'sort' => 40],
                    ['key' => 'r5', 'body' => self::t('Riset dulu era-nya sebelum membeli gear — tanya dulu sebelum belanja.', 'Research your era before buying gear — ask before you spend.'), 'sort' => 50],
                ],
            ],
            [
                'page_key' => 'join', 'block_key' => 'join.steps', 'layout' => 'numbered_steps', 'sort' => 60,
                'heading' => self::t('Proses Keanggotaan', 'Membership Process'),
                'items' => [
                    ['key' => 's1', 'value' => '01', 'title' => self::t('Pendaftaran Awal', 'Initial Application'), 'body' => self::t('Isi formulir di bawah dengan latar belakang dan motivasimu.', 'Submit the form below with your background and motivation.'), 'sort' => 10],
                    ['key' => 's2', 'value' => '02', 'title' => self::t('Review Pendaftaran', 'Application Review'), 'body' => self::t('Admin menilai kecocokan dengan standar kami dan kebutuhan roster saat ini.', 'Admins review fit against our standards and current roster needs.'), 'sort' => 20],
                    ['key' => 's3', 'value' => '03', 'title' => self::t('Interview', 'Interview'), 'body' => self::t('Obrolan singkat — biasanya lewat WhatsApp atau telepon.', 'A short conversation — usually over WhatsApp or a call.'), 'sort' => 30],
                    ['key' => 's4', 'value' => '04', 'title' => self::t('Perkenalan', 'Introduction'), 'body' => self::t('Temui tim di acara kumpul atau hari latihan sebelum berkomitmen.', 'Meet the team at a gathering or training day before committing.'), 'sort' => 40],
                    ['key' => 's5', 'value' => '05', 'title' => self::t('Persetujuan Keanggotaan', 'Membership Approval'), 'body' => self::t('Callsign dan Member ID diterbitkan; roster dan patch menyusul.', 'Callsign and Member ID issued; roster and patch follow.'), 'sort' => 50],
                ],
            ],

            // ---------- Global ----------
            ['page_key' => '_global', 'block_key' => '_global.disclaimer', 'layout' => 'prose', 'sort' => 10, 'body' => self::t('{strong} adalah komunitas sipil airsoft dan military impression yang independen, terinspirasi oleh MARSOC dan Marine Raiders. M.R.T bukan bagian dari, tidak berafiliasi dengan, dan tidak mewakili United States Marine Corps maupun MARSOC, serta tidak mengklaim dukungan resmi apa pun.', '{strong} is an independent, civilian airsoft and military-impression community inspired by MARSOC and the Marine Raiders. It is not part of, not affiliated with, and does not represent the United States Marine Corps or MARSOC, and claims no official endorsement.')],
        ];

        foreach ($blocks as $block) {
            $block['is_published'] = true;
            PageBlock::query()->updateOrCreate(['block_key' => $block['block_key']], $block);
        }
    }

    /**
     * Tiny local helper — {id, en} pair, matching the frontend's own
     * bilingual-field convention exactly.
     *
     * @return array{id: string, en: string}
     */
    private static function t(string $id, string $en): array
    {
        return ['id' => $id, 'en' => $en];
    }
}
