<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MediaCategory;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Era;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\WeaponVariant;
use Database\Seeders\Data\ActivityFixture;
use Database\Seeders\Data\ImpressionFixture;
use Database\Seeders\Data\MediaFixture;
use Database\Seeders\Data\PersonnelFixture;
use Database\Seeders\Data\PublicationFixture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

/**
 * Initial content load: the SPA's original sample records into MongoDB and
 * the media library. Everything is flagged is_synthetic except the 7
 * real-steel equipment reference photos (see MediaItem::type vs
 * is_synthetic). Idempotent: updateOrCreate on natural keys, and media is
 * only attached when the collection is still empty.
 */
final class ContentSeeder extends Seeder
{
    /** media key => weapon key (soft pointer; photos are uploaded twice by design) */
    private const WEAPON_FOR_MEDIA = [
        'eq-mk18-urgi' => 'mk18-urgi',
        'eq-cqbr-block-ii' => 'cqbr-block-ii',
        'eq-scar' => 'scar-mk16-17',
        'eq-mk48' => 'mk48',
        'eq-m110' => 'm110',
        'eq-glock17' => 'glock17',
        'eq-glock19' => 'glock19',
    ];

    /** activity slug => sample banner image */
    private const BANNER_FOR_ACTIVITY = [
        'grid-zero-training-day' => 'training-day-1',
        'operation-anoa-shield' => 'milsim-event-1',
        'tadulako-cup-skirmish-3' => 'skirmish-1',
        'joint-field-exercise' => 'joint-field-1',
        'patch-and-brotherhood-night' => 'team-photo-1',
        'return-to-marpat-clinic' => 'historical-impression-1',
    ];

    /** publication slug => banner image (sample set) */
    private const COVER_FOR_PUBLICATION = [
        'building-an-accurate-marine-raider-impression' => '/images/fixtures/unit/eras/return-to-marpat-2.jpg',
        'marine-raiders-equipment-reference' => '/images/fixtures/unit/mk18-cqbr-10-3-geissele-mk16.jpg',
        'grid-zero-after-action' => '/images/fixtures/media-demo/training-day-1.jpg',
        'evolution-of-marsoc-combat-uniforms' => '/images/fixtures/unit/eras/early-marsoc-1.jpg',
    ];

    public function run(): void
    {
        // Conversions run inline: the seeder must not depend on a queue worker.
        config(['media-library.queue_conversions_by_default' => false]);

        $eraIds = $this->eras();
        $this->weapons();
        $this->personnel($eraIds);
        $this->activities();
        $this->media();
        $this->publications();
    }

    /** @return array<string, string> era key => era id */
    private function eras(): array
    {
        $ids = [];

        foreach (ImpressionFixture::eras() as $i => $row) {
            $era = Era::query()->updateOrCreate(['key' => $row['key']], [
                'name' => $row['name'],
                'uniform' => $row['uniform'],
                'gear' => $row['gear'],
                'helmet' => $row['helmet'],
                'sort' => ($i + 1) * 10,
                'is_published' => true,
            ]);
            $this->attach($era, 'photos', $row['images']);
            $ids[$row['key']] = (string) $era->getKey();
        }

        return $ids;
    }

    private function weapons(): void
    {
        $n = 0;

        foreach (ImpressionFixture::weapons() as $category) {
            foreach ($category['items'] as $item) {
                $weapon = WeaponVariant::query()->updateOrCreate(['key' => $item['key']], [
                    'category' => $category['categoryKey'],
                    'name' => $item['name'],
                    'sort' => (++$n) * 10,
                    'is_published' => true,
                ]);
                $this->attach($weapon, 'photos', $item['images']);
            }
        }
    }

    /** @param  array<string, string>  $eraIds */
    private function personnel(array $eraIds): void
    {
        $eraKeyFor = fn (string $en): string => match (true) {
            str_contains($en, 'MARPAT') => 'return-to-marpat',
            str_contains($en, 'MultiCam') => 'multicam',
            default => 'early-marsoc',
        };

        foreach (PersonnelFixture::all() as $i => $row) {
            Personnel::query()->updateOrCreate(['callsign' => $row['callsign']], [
                'member_id' => $row['memberId'],
                'role' => $row['role'],
                'status' => $row['statusKey'],
                'joined_on' => $row['joinDate'],
                'era_id' => $eraIds[$eraKeyFor($row['impressionEra']['en'])],
                'impression_focus' => $row['impressionFocus'],
                'primary_platform' => $row['primaryPlatform'],
                'secondary_platform' => $row['secondaryPlatform'] === '—' ? null : $row['secondaryPlatform'],
                'grid' => $row['grid'],
                'bio' => $row['bio'],
                'sort' => ($i + 1) * 10,
                'is_published' => true,
                'is_synthetic' => true,
            ]);
        }
    }

    private function activities(): void
    {
        $categories = [];

        foreach (ActivityFixture::all() as $row) {
            $slug = Str::slug($row['category']['en']);
            $categories[$slug] ??= ActivityCategory::query()->updateOrCreate(['slug' => $slug], [
                'name' => $row['category'],
                'sort' => (count($categories) + 1) * 10,
                'is_published' => true,
            ]);

            $activity = Activity::query()->updateOrCreate(['slug' => $row['slug']], [
                'title' => $row['title'],
                'activity_category_id' => (string) $categories[$slug]->getKey(),
                'occurred_on' => $row['date'],
                'location' => $row['location'],
                'grid' => $row['grid'],
                'participants' => $row['participants'],
                'related_member_callsigns' => $row['relatedMembers'],
                'overview' => $row['overview'],
                'content' => self::activityContent($row),
                'is_published' => true,
                'is_synthetic' => true,
            ]);

            if (isset(self::BANNER_FOR_ACTIVITY[$row['slug']])) {
                $this->attach($activity, 'banner', ['/images/fixtures/media-demo/'.self::BANNER_FOR_ACTIVITY[$row['slug']].'.jpg']);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{id: string, en: string}
     */
    private static function activityContent(array $row): array
    {
        $wrap = fn (string $text): string => '<p>'.e($text).'</p>';

        if ($row['slug'] === 'operation-anoa-shield') {
            return [
                'id' => $wrap($row['notes']['id'])
                    .'<h2>Alur hari itu</h2>'
                    .'<ul><li>Briefing bersama marshal staff dan pembagian elemen.</li><li>Fase pertahanan: defend-and-hold di tiga titik.</li><li>Debrief singkat dan evaluasi per tim.</li></ul>'
                    .'<p>Disiplin radio dan pengecekan eye protection tetap menjadi aturan tetap sebelum setiap fase dimulai.</p>',
                'en' => $wrap($row['notes']['en'])
                    .'<h2>How the day ran</h2>'
                    .'<ul><li>Joint briefing with the marshal staff and element assignments.</li><li>Defence phase: defend-and-hold across three positions.</li><li>Short debrief and per-team review.</li></ul>'
                    .'<p>Radio discipline and eye-protection checks stayed the standing order before every phase.</p>',
            ];
        }

        return ['id' => $wrap($row['notes']['id']), 'en' => $wrap($row['notes']['en'])];
    }

    private function media(): void
    {
        $categoryFor = [];
        foreach (MediaCategory::cases() as $case) {
            $categoryFor[$case->labelEn()] = $case->value;
        }

        foreach (MediaFixture::all() as $i => $row) {
            $item = MediaItem::query()->updateOrCreate(['key' => $row['id']], [
                'category' => $categoryFor[$row['category']],
                'type' => $row['type'],
                'title' => $row['title'],
                'caption' => $row['caption'],
                'year_label' => $row['year'],
                'related_activity_slug' => $row['relatedActivity'] ?? null,
                'weapon_id' => self::WEAPON_FOR_MEDIA[$row['id']] ?? null,
                'sort' => ($i + 1) * 10,
                'is_published' => true,
                // The 7 equipment reference photos are genuine content.
                'is_synthetic' => $row['type'] !== 'photo',
            ]);
            $this->attach($item, 'photos', $row['photos']);
        }
    }

    private function publications(): void
    {
        $categories = [];

        foreach (PublicationFixture::all() as $row) {
            $slug = Str::slug($row['category']['en']);
            $categories[$slug] ??= PublicationCategory::query()->updateOrCreate(['slug' => $slug], [
                'name' => $row['category'],
                'sort' => (count($categories) + 1) * 10,
                'is_published' => true,
            ]);

            $publication = Publication::query()->updateOrCreate(['slug' => $row['slug']], [
                'title' => $row['title'],
                'publication_category_id' => (string) $categories[$slug]->getKey(),
                'author_callsign' => $row['author'],
                'published_on' => $row['date'],
                'excerpt' => $row['excerpt'],
                'content' => self::publicationContent($row),
                'sources' => array_map(fn (string $label): array => ['label' => $label, 'url' => null], $row['sources']),
                'related_activity_slug' => $row['relatedActivity'] ?? null,
                'is_published' => true,
                'is_synthetic' => true,
            ]);

            if (isset(self::COVER_FOR_PUBLICATION[$row['slug']])) {
                $this->attach($publication, 'cover', [self::COVER_FOR_PUBLICATION[$row['slug']]]);
            }
        }
    }

    /**
     * Article body as the rich editor would store it. Most articles are the
     * original paragraphs wrapped in <p>; the impression guide is a fully
     * worked example with headings, a list, a pull quote and inline images
     * (files copied to the public disk, referenced as /storage/publications/*).
     *
     * @param  array<string, mixed>  $row
     * @return array{id: string, en: string}
     */
    private static function publicationContent(array $row): array
    {
        if ($row['slug'] === 'building-an-accurate-marine-raider-impression') {
            return self::impressionGuide();
        }

        $paragraphs = fn (array $list): string => implode('', array_map(fn (string $t): string => '<p>'.e($t).'</p>', $list));

        return ['id' => $paragraphs($row['body']['id']), 'en' => $paragraphs($row['body']['en'])];
    }

    /** @return array{id: string, en: string} */
    private static function impressionGuide(): array
    {
        foreach (['early-marsoc-1', 'multicam-1', 'return-to-marpat-1'] as $file) {
            self::publishInlineImage($file);
        }

        $img = fn (string $file, string $alt): string => '<img src="/storage/publications/'.$file.'.jpg" alt="'.e($alt).'">';

        return [
            'id' => '<p>Impresi yang akurat dimulai dari satu keputusan: pilih satu era, lalu bangun ke arah itu secara konsisten. Loadout campuran era terbaca lebih lemah dibanding loadout murah yang konsisten.</p>'
                .'<h2>Tiga era acuan</h2>'
                .'<p>M.R.T memakai tiga era sebagai acuan impresi Marine Raider. Pilih salah satu — jangan mencampur.</p>'
                .'<h3>Early MARSOC (Afghanistan)</h3>'
                .$img('early-marsoc-1', 'Early MARSOC di Afghanistan')
                .'<p>MCCUU atau FROG MARPAT Woodland/Desert dengan webbing Coyote Brown, pasangan standar untuk MARPAT. Helm memakai cover MARPAT atau shell coyote/tan.</p>'
                .'<h3>MultiCam (sebelum 2025)</h3>'
                .$img('multicam-1', 'Era MultiCam')
                .'<p>Combat shirt dan pant MultiCam yang menyelaraskan Raiders dengan unit SOF lain, dengan helm high-cut bercover MultiCam dan headset over-ear di rail.</p>'
                .'<h3>Return to MARPAT (2025 →)</h3>'
                .$img('return-to-marpat-1', 'Era Return to MARPAT')
                .'<p>FROG MARPAT sebagai seragam interim, sesuai memo Komandan MARSOC (20 Sep 2024, efektif 1 Jan 2025) yang mengakhiri MultiCam. Gear berwarna OD Green.</p>'
                .'<blockquote><p>Riset dulu era dan role-nya sebelum membeli tampilannya.</p></blockquote>'
                .'<h2>Sebelum belanja</h2>'
                .'<ul><li>Tentukan era dan role terlebih dahulu.</li><li>Cocokkan seragam, webbing, dan helm dengan era yang sama.</li><li>Tanyakan dulu ke tim sebelum membeli gear mahal.</li></ul>'
                .'<p>Semua acuan di atas bersumber dan bertanggal — lihat daftar sumber di bawah artikel.</p>',
            'en' => '<p>An accurate impression starts with one decision: pick an era, then build toward it consistently. A mixed-era loadout reads weaker than a cheaper, consistent one.</p>'
                .'<h2>Three reference eras</h2>'
                .'<p>M.R.T uses three eras as Marine Raider impression references. Pick one — do not mix.</p>'
                .'<h3>Early MARSOC (Afghanistan)</h3>'
                .$img('early-marsoc-1', 'Early MARSOC in Afghanistan')
                .'<p>MCCUU or FROG MARPAT Woodland/Desert with Coyote Brown webbing, the standard pairing with MARPAT. Helmet with a MARPAT cover or a coyote/tan shell.</p>'
                .'<h3>MultiCam (before 2025)</h3>'
                .$img('multicam-1', 'The MultiCam era')
                .'<p>MultiCam combat shirt and pant, matching Raiders to other SOF units, with a high-cut helmet under a MultiCam cover and an over-ear headset on the rail.</p>'
                .'<h3>Return to MARPAT (2025 →)</h3>'
                .$img('return-to-marpat-1', 'The Return to MARPAT era')
                .'<p>FROG MARPAT as an interim uniform, per the MARSOC Commandant memo (20 Sep 2024, effective 1 Jan 2025) ending MultiCam. OD Green gear.</p>'
                .'<blockquote><p>Research the era and the role before buying the look.</p></blockquote>'
                .'<h2>Before you spend</h2>'
                .'<ul><li>Decide on an era and a role first.</li><li>Match uniform, webbing and helmet to the same era.</li><li>Ask the team before buying expensive gear.</li></ul>'
                .'<p>Every reference above is sourced and dated — see the source list under the article.</p>',
        ];
    }

    /** Copies a sample era photo to the public disk once, as the editor's upload would. */
    private static function publishInlineImage(string $name): void
    {
        $disk = Storage::disk('public');
        $target = "publications/{$name}.jpg";

        if (! config('mrt.seed_media') || $disk->exists($target)) {
            return;
        }

        $disk->put($target, (string) file_get_contents(database_path("seeders/images/unit/eras/{$name}.jpg")));
    }

    /** @param  list<string>  $urls  fixture-style '/images/fixtures/...' paths */
    private function attach(HasMedia $model, string $collection, array $urls): void
    {
        if (! config('mrt.seed_media') || $model->getMedia($collection)->isNotEmpty()) {
            return;
        }

        foreach ($urls as $url) {
            $path = database_path('seeders'.str_replace('/images/fixtures', '/images', $url));
            $model->addMedia($path)->preservingOriginal()->toMediaCollection($collection);
        }
    }
}
