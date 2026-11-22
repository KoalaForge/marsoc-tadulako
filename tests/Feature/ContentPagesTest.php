<?php

declare(strict_types=1);

use App\Filament\Pages\Content\HomeContentPage;
use App\Filament\Pages\Settings\SiteSettingsPage;
use App\Models\PageBlock;
use App\Models\SiteSettings;
use App\Models\User;
use Database\Seeders\PageBlockSeeder;
use Database\Seeders\SiteSettingsSeeder;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function (): void {
    (new PageBlockSeeder)->run();
    $this->actingAs(User::create([
        'name' => 'Admin',
        'email' => 'cp-'.Str::random(10).'@mrt.test',
        'password' => bcrypt('password'),
        'is_admin' => true,
    ]));
});

it('renders every content editor page', function (string $slug): void {
    $this->get("/admin/content/{$slug}")->assertSuccessful();
})->with(['home', 'about', 'personnel', 'impression', 'activities', 'media', 'publications', 'join']);

it('saves an edited creed line and shows it on the public home page', function (): void {
    $component = Livewire::test(HomeContentPage::class);
    $items = $component->get('data.creed.items');
    $first = array_key_first($items);

    $component
        ->set("data.creed.items.{$first}.label", 'WE ARE ROOTED IN TADULAKO (EDITED).')
        ->call('save')
        ->assertHasNoFormErrors();

    expect(PageBlock::forKey('home.creed')->items[0]['label'])->toBe('WE ARE ROOTED IN TADULAKO (EDITED).');
    $this->get('/id')->assertSee('TADULAKO (EDITED)', false);
});

it('keeps the other locale when only one locale is edited', function (): void {
    Livewire::test(HomeContentPage::class)
        ->set('data.hero.heading.en', 'Edited EN heading')
        ->call('save')
        ->assertHasNoFormErrors();

    $hero = PageBlock::forKey('home.hero');
    expect($hero->getTranslation('heading', 'en'))->toBe('Edited EN heading')
        ->and($hero->getTranslation('heading', 'id'))->toContain('M.R.T');
});

it('edits site settings and persists them', function (): void {
    (new SiteSettingsSeeder)->run();
    $this->get('/admin/settings/site')->assertSuccessful();

    Livewire::test(SiteSettingsPage::class)
        ->set('data.whatsapp_number', '6281234567890')
        ->set('data.community_name.id', 'M.R.T Uji')
        ->call('save')
        ->assertHasNoFormErrors();

    $s = SiteSettings::query()->first();
    expect($s->whatsapp_number)->toBe('6281234567890')
        ->and($s->getTranslation('community_name', 'id'))->toBe('M.R.T Uji');
});

it('renders an edited hero heading on every page-hero page', function (string $page, string $path): void {
    $block = PageBlock::forKey("{$page}.hero");
    $block->setTranslation('heading', 'id', "HERO-EDIT-{$page}");
    $block->save();

    $this->get("/id{$path}")->assertSee("HERO-EDIT-{$page}", false);
})->with([
    ['about', '/about'], ['personnel', '/personnel'], ['impression', '/impression'],
    ['activities', '/activities'], ['media', '/media'], ['publications', '/publications'], ['join', '/join'],
]);

it('renders body blocks from the CMS on about, impression and join', function (string $block, string $path, string $needle): void {
    $b = PageBlock::forKey($block);
    $b->items = collect($b->items)->map(fn ($i, $k) => $k === 0 ? array_merge($i, isset($i['label']) && is_string($i['label']) ? ['label' => $needle] : ['body' => ['id' => $needle, 'en' => $needle]]) : $i)->all();
    $b->save();

    $this->get("/id{$path}")->assertSee($needle, false);
})->with([
    ['about.values', '/about', 'VALUE-EDIT-X'],
    ['impression.headgear', '/impression', 'HEADGEAR-EDIT-X'],
    ['join.rules', '/join', 'RULES-EDIT-X'],
]);

it('renders the edited home hero lede', function (): void {
    $block = PageBlock::forKey('home.hero');
    $block->setTranslation('subheading', 'id', 'LEDE-EDIT-HOME');
    $block->save();

    $this->get('/id')->assertSee('LEDE-EDIT-HOME', false);
});
