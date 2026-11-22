<?php

declare(strict_types=1);

use App\Filament\Pages\Content\HomeContentPage;
use App\Models\Era;
use App\Models\PageBlock;
use App\Models\Personnel;
use App\Models\User;
use App\Models\WeaponVariant;
use Database\Seeders\PageBlockSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

function inertiaProps(string $html): array
{
    preg_match('/<script data-page="app" type="application\/json">(.*?)<\/script>/s', $html, $m);

    return json_decode($m[1], true)['props'];
}

function sampleImage(string $name): string
{
    return database_path("seeders/images/media-demo/{$name}.jpg");
}

beforeEach(function (): void {
    Storage::fake('public');
});

it('has no teaser image until one exists anywhere', function (): void {
    (new PageBlockSeeder)->run();

    expect(inertiaProps($this->get('/id')->getContent())['teaserImage'])->toBeNull();
});

it('lets the admin set the teaser photo from the Home content page, and shows it on the home page', function (): void {
    (new PageBlockSeeder)->run();
    $this->actingAs(User::create(['name' => 'A', 'email' => Str::random(8).'@mrt.test', 'password' => bcrypt('x'), 'is_admin' => true]));

    Livewire::test(HomeContentPage::class)
        ->fillForm(['image_home_impression-teaser' => UploadedFile::fake()->image('t.jpg', 1600, 900)])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(PageBlock::forKey('home.impression-teaser')->getFirstMedia('image'))->not->toBeNull();

    $teaser = inertiaProps($this->get('/id')->getContent())['teaserImage'];
    expect($teaser)->toBeString()->toContain('/storage/');
});

it('falls back to the first Impression photo when no teaser photo is set', function (): void {
    (new PageBlockSeeder)->run();

    $weapon = WeaponVariant::create(['key' => 'w-'.Str::random(5), 'category' => 'rifle', 'name' => 'Test Rifle', 'sort' => 10, 'is_published' => true]);
    $weapon->addMedia(sampleImage('skirmish-1'))->preservingOriginal()->toMediaCollection('photos');

    $teaser = inertiaProps($this->get('/id')->getContent())['teaserImage'];
    expect($teaser)->toBeString()->toContain('/storage/');

    // An explicit photo on the block wins over the fallback.
    PageBlock::forKey('home.impression-teaser')->addMedia(sampleImage('training-day-1'))->preservingOriginal()->toMediaCollection('image');
    $explicit = inertiaProps($this->get('/id')->getContent())['teaserImage'];
    expect($explicit)->not->toBe($teaser);
});

it('publishes a portrait only with consent and an uploaded photo', function (): void {
    $era = Era::create(['key' => 'e-'.Str::random(5), 'name' => 'E', 'uniform' => ['en' => 'x', 'id' => 'x'], 'gear' => ['en' => 'x', 'id' => 'x'], 'helmet' => ['en' => 'x', 'id' => 'x']]);

    $make = function (string $callsign, bool $consent, bool $withPhoto) use ($era): Personnel {
        $p = Personnel::create([
            'callsign' => $callsign, 'member_id' => 'M-'.Str::random(4), 'role' => ['en' => 'r', 'id' => 'r'],
            'status' => 'active', 'era_id' => (string) $era->getKey(), 'bio' => ['en' => 'b', 'id' => 'b'],
            'is_published' => true, 'portrait_consent' => $consent, 'sort' => 10,
        ]);

        if ($withPhoto) {
            $p->addMedia(sampleImage('portrait-1'))->preservingOriginal()->toMediaCollection('portrait');
        }

        return $p;
    };

    $make('CONSENT-ON', true, true);
    $make('CONSENT-OFF', false, true);
    $make('NO-PHOTO', true, false);

    $members = collect(inertiaProps($this->get('/id/personnel')->getContent())['members'])->keyBy('callsign');

    expect($members['CONSENT-ON']['portrait'])->toBeString()->toContain('/storage/')
        ->and($members['CONSENT-OFF']['portrait'])->toBeNull()
        ->and($members['NO-PHOTO']['portrait'])->toBeNull();

    // Same rule on the profile page, and the unpublished photo never leaks into the HTML.
    $off = $this->get('/id/personnel/consent-off')->assertSuccessful();
    expect(inertiaProps($off->getContent())['member']['portrait'])->toBeNull();
    expect(inertiaProps($this->get('/id/personnel/consent-on')->getContent())['member']['portrait'])->toBeString();
});
