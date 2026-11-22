<?php

declare(strict_types=1);

use App\Models\Activity;
use App\Models\SiteSettings;
use Database\Seeders\DatabaseSeeder;

beforeEach(fn () => $this->seed(DatabaseSeeder::class));

it('returns 200 for every public route in both locales', function (string $locale, string $path): void {
    $this->get("/{$locale}{$path}")->assertSuccessful();
})->with([
    ['id', ''], ['en', ''],
    ['id', '/about'], ['en', '/about'],
    ['id', '/personnel'], ['en', '/personnel'],
    ['id', '/personnel/raider-01'], ['en', '/personnel/raider-01'],
    ['id', '/impression'], ['en', '/impression'],
    ['id', '/activities'], ['en', '/activities'],
    ['id', '/activities/grid-zero-training-day'], ['en', '/activities/grid-zero-training-day'],
    ['id', '/media'], ['en', '/media'],
    ['id', '/media/eq-mk18-urgi'], ['en', '/media/eq-mk18-urgi'],
    ['id', '/publications'], ['en', '/publications'],
    ['id', '/publications/understanding-marsoc-brief-history'], ['en', '/publications/understanding-marsoc-brief-history'],
    ['id', '/join'], ['en', '/join'],
]);

it('redirects the bare root to a locale-prefixed path', function (): void {
    $this->get('/')->assertRedirect();
});

it('rejects an unsupported locale segment', function (): void {
    $this->get('/fr')->assertNotFound();
});

it('returns a real 404 for unknown records', function (string $path): void {
    $this->get($path)->assertNotFound();
})->with([
    '/id/personnel/nobody', '/id/activities/nope', '/id/media/nope', '/id/publications/nope',
]);

it('hides unpublished records', function (): void {
    Activity::query()->where('slug', 'grid-zero-training-day')->update(['is_published' => false]);

    $this->get('/id/activities/grid-zero-training-day')->assertNotFound();
});

it('reverse-links personnel to the activities that list them', function (): void {
    $page = $this->get('/id/personnel/raider-01')->assertSuccessful()->getContent();

    expect($page)->toContain('grid-zero-training-day')->toContain('operation-anoa-shield');
});

it('renders per-locale SEO meta in the server HTML', function (): void {
    $html = $this->get('/en/activities/grid-zero-training-day')->assertSuccessful()->getContent();

    $base = rtrim((string) config('app.url'), '/');

    expect($html)
        ->toContain('<title>Grid Zero: Foundational Training Day — M.R.T</title>')
        ->toContain("rel=\"canonical\" href=\"{$base}/en/activities/grid-zero-training-day\"")
        ->toContain("hreflang=\"id\" href=\"{$base}/id/activities/grid-zero-training-day\"")
        ->toContain('property="og:image"');
});

it('serves robots.txt disallowing admin and a sitemap with hreflang alternates', function (): void {
    $this->get('/robots.txt')->assertSuccessful()->assertSee('Disallow: /admin')->assertSee('sitemap.xml');

    $xml = $this->get('/sitemap.xml')->assertSuccessful()->getContent();
    expect($xml)->toContain('/id/personnel/raider-01')->toContain('hreflang="en"');
});

it('takes the WhatsApp number from site settings, not hardcoded markup', function (): void {
    SiteSettings::query()->first()->update(['whatsapp_number' => '6281111111111']);

    $this->get('/id/join')->assertSee('https:\/\/wa.me\/6281111111111', false);
});
