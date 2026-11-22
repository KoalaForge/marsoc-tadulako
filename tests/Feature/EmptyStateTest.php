<?php

declare(strict_types=1);

// Deliberately no seeding: a fresh install (or a purge of all demo data)
// must render every public page without errors.
it('renders every list page with no data', function (string $locale, string $path): void {
    $this->get("/{$locale}{$path}")->assertSuccessful();
})->with(function (): array {
    $rows = [];
    foreach (['id', 'en'] as $locale) {
        foreach (['', '/about', '/personnel', '/impression', '/activities', '/media', '/publications', '/join'] as $path) {
            $rows[] = [$locale, $path];
        }
    }

    return $rows;
});

it('ships empty collections (not nulls) for list props when there is no data', function (): void {
    $props = fn (string $path): array => json_decode(
        (string) preg_replace('/.*<script data-page="app" type="application\/json">(.*?)<\/script>.*/s', '$1', $this->get($path)->getContent()),
        true,
    )['props'];

    expect($props('/id/personnel')['members'])->toBe([])
        ->and($props('/id/activities')['activities'])->toBe([])
        ->and($props('/id/media')['items'])->toBe([])
        ->and($props('/id/publications')['publications'])->toBe([])
        ->and($props('/id')['featuredMedia'])->toBe([])
        ->and($props('/id/impression')['eras'])->toBe([]);
});

it('hides the WhatsApp link when no number is configured', function (): void {
    $html = $this->get('/id/join')->getContent();

    expect($html)->not->toContain('wa.me');
});
