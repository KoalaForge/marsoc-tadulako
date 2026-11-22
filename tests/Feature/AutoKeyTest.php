<?php

declare(strict_types=1);

use App\Filament\Resources\MediaItems\Pages\CreateMediaItem;
use App\Filament\Resources\MediaItems\Pages\EditMediaItem;
use App\Models\Activity;
use App\Models\Era;
use App\Models\MediaItem;
use App\Models\Publication;
use App\Models\User;
use App\Models\WeaponVariant;
use Illuminate\Support\Str;
use Livewire\Livewire;

function mediaItemData(array $overrides = []): array
{
    return array_merge([
        'category' => 'equipment-photography',
        'type' => 'photo',
        'title' => ['id' => 'Foto Latihan Dasar', 'en' => 'Basic Training Photo'],
    ], $overrides);
}

it('generates a media item key from the Indonesian title when none is given', function (): void {
    $item = MediaItem::create(mediaItemData());

    expect($item->key)->toBe('foto-latihan-dasar')
        ->and($item->getRouteKey())->toBe('foto-latihan-dasar');
});

it('keeps keys unique by suffixing duplicates', function (): void {
    MediaItem::create(mediaItemData());
    $second = MediaItem::create(mediaItemData());
    $third = MediaItem::create(mediaItemData(['key' => null]));

    expect($second->key)->toBe('foto-latihan-dasar-2')
        ->and($third->key)->toBe('foto-latihan-dasar-3');
});

it('respects an explicitly provided key', function (): void {
    expect(MediaItem::create(mediaItemData(['key' => 'eq-mk18-urgi']))->key)->toBe('eq-mk18-urgi');
});

it('generates era and weapon keys from their plain names', function (): void {
    $era = Era::create(['name' => 'Return to MARPAT (2025 →)', 'uniform' => ['en' => 'x', 'id' => 'x'], 'gear' => ['en' => 'x', 'id' => 'x'], 'helmet' => ['en' => 'x', 'id' => 'x']]);
    $weapon = WeaponVariant::create(['category' => 'rifle', 'name' => 'Mk18 Mod 1 — Daniel Defense RIS II']);

    expect($era->key)->toBe('return-to-marpat-2025')
        ->and($weapon->key)->toBe('mk18-mod-1-daniel-defense-ris-ii');
});

it('lets the admin leave the key empty when creating a media item, and requires it when editing', function (): void {
    $this->actingAs(User::create(['name' => 'A', 'email' => Str::random(8).'@mrt.test', 'password' => bcrypt('x'), 'is_admin' => true]));

    Livewire::test(CreateMediaItem::class)
        ->fillForm(['title' => ['id' => 'Seri Potret Anggota', 'en' => 'Member Portrait Series'], 'category' => 'individual-portraits', 'type' => 'photo'])
        ->call('create')
        ->assertHasNoFormErrors();

    $item = MediaItem::query()->where('key', 'seri-potret-anggota')->first();
    expect($item)->not->toBeNull();

    // Spaces / capitals typed by hand are normalised to a URL-safe key.
    Livewire::test(EditMediaItem::class, ['record' => $item->key])
        ->fillForm(['key' => ''])
        ->call('save')
        ->assertHasFormErrors(['key' => 'required']);

    Livewire::test(EditMediaItem::class, ['record' => $item->key])
        ->fillForm(['key' => 'Seri Potret 2025'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($item->fresh()->key)->toBe('seri-potret-2025');
});

it('lists media, activities and publications newest first', function (): void {
    $props = function (string $path): array {
        preg_match('/<script data-page="app" type="application\/json">(.*?)<\/script>/s', $this->get($path)->getContent(), $m);

        return json_decode($m[1], true)['props'];
    };

    foreach ([['old', 30], ['new', 1], ['mid', 10]] as [$name, $daysAgo]) {
        $item = MediaItem::create(mediaItemData(['key' => "order-{$name}", 'title' => ['id' => $name, 'en' => $name]]));
        $item->created_at = now()->subDays($daysAgo);
        $item->save();

        Activity::create(['slug' => "act-{$name}", 'title' => ['id' => $name, 'en' => $name], 'occurred_on' => now()->subDays($daysAgo), 'overview' => ['id' => 'x', 'en' => 'x']]);
        Publication::create(['slug' => "pub-{$name}", 'title' => ['id' => $name, 'en' => $name], 'excerpt' => ['id' => 'x', 'en' => 'x'], 'content' => ['id' => '<p>x</p>', 'en' => '<p>x</p>'], 'published_on' => now()->subDays($daysAgo)]);
    }

    expect(array_column($props('/id/media')['items'], 'id'))->toBe(['order-new', 'order-mid', 'order-old'])
        ->and(array_column($props('/id/activities')['activities'], 'slug'))->toBe(['act-new', 'act-mid', 'act-old'])
        ->and(array_column($props('/id/publications')['publications'], 'slug'))->toBe(['pub-new', 'pub-mid', 'pub-old']);
});
