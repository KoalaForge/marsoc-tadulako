<?php

declare(strict_types=1);

use App\Filament\Widgets\SyntheticDataWidget;
use App\Filament\Widgets\TranslationGapsWidget;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\User;
use App\Support\Html;
use App\Support\TranslationGaps;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('never uses table-column searchable() or whereRaw (broken on MongoDB)', function (): void {
    $offenders = [];

    $scan = function (string $dir, string $needle) use (&$offenders): void {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php'
                && str_contains(file_get_contents($file->getPathname()), $needle)) {
                $offenders[] = $file->getPathname();
            }
        }
    };

    foreach (glob(app_path('Filament/Resources/*/Tables'), GLOB_ONLYDIR) as $dir) {
        $scan($dir, '->searchable(');
    }
    $scan(app_path(), 'whereRaw');

    expect($offenders)->toBe([]);
});

it('reports a translation gap only when one locale is filled and another blank', function (): void {
    $this->seed(DatabaseSeeder::class);
    expect(TranslationGaps::scan())->toBe([]);

    Activity::query()->where('slug', 'grid-zero-training-day')->first()
        ->setTranslation('title', 'en', '')->save();

    $gaps = TranslationGaps::scan();
    expect($gaps)->toHaveCount(1)
        ->and($gaps[0]['label'])->toBe('grid-zero-training-day')
        ->and($gaps[0]['fields'])->toBe(['title']);
});

it('purge command is a dry run by default and deletes only synthetic records with --force', function (): void {
    $this->seed(DatabaseSeeder::class);
    $before = Personnel::query()->count();

    $this->artisan('content:purge-synthetic')->assertSuccessful();
    expect(Personnel::query()->count())->toBe($before);

    $this->artisan('content:purge-synthetic --force')->assertSuccessful();
    expect(Personnel::query()->count())->toBe(0)
        ->and(MediaItem::query()->count())->toBe(7); // real-steel equipment photos survive
});

it('renders the translation-gaps and synthetic-data widgets', function (): void {
    $this->seed(DatabaseSeeder::class);
    $this->actingAs(User::create(['name' => 'A', 'email' => Str::random(8).'@mrt.test', 'password' => bcrypt('x'), 'is_admin' => true]));

    // Dashboard widgets are lazy Livewire components, so test them directly.
    Livewire::test(TranslationGapsWidget::class)->assertSee('Semua konten sudah dua bahasa');
    Livewire::test(SyntheticDataWidget::class)->assertSee('Data demo');
});

it('reflects an admin edit on the cached index page right after saving', function (): void {
    $this->seed(DatabaseSeeder::class);

    $this->get('/id/activities')->assertSee('Grid Zero: Hari Latihan Dasar', false);

    Activity::query()->where('slug', 'grid-zero-training-day')->first()
        ->setTranslation('title', 'id', 'Judul Baru Uji')->save();

    $this->get('/id/activities')->assertSee('Judul Baru Uji', false)->assertDontSee('Hari Latihan Dasar', false);
});

it('never leaks raw JSON or translation maps as text on any public page', function (): void {
    $this->seed(DatabaseSeeder::class);

    $paths = ['', '/about', '/personnel', '/personnel/raider-01', '/impression', '/activities', '/activities/grid-zero-training-day',
        '/media', '/media/eq-mk18-urgi', '/publications', '/publications/understanding-marsoc-brief-history', '/join'];

    $leaks = [];
    $walk = function (mixed $node, string $trail) use (&$walk, &$leaks): void {
        if (is_string($node) && preg_match('/^\s*[\[{]\s*"/', $node)) {
            $leaks[] = "{$trail}: ".substr($node, 0, 60);
        } elseif (is_array($node)) {
            foreach ($node as $k => $v) {
                $walk($v, "{$trail}.{$k}");
            }
        }
    };

    foreach (['id', 'en'] as $locale) {
        foreach ($paths as $path) {
            $html = $this->get("/{$locale}{$path}")->assertSuccessful()->getContent();
            preg_match('/<script data-page="app" type="application\/json">(.*?)<\/script>/s', $html, $m);
            $props = json_decode($m[1], true)['props'];
            unset($props['translations']);
            $walk($props, "{$locale}{$path}");
        }
    }

    expect($leaks)->toBe([]);
});

it('sanitizes rich activity content on output', function (): void {
    $this->seed(DatabaseSeeder::class);

    Activity::query()->where('slug', 'grid-zero-training-day')->first()
        ->setTranslation('content', 'id', '<p onclick="x()">Halo <strong>dunia</strong></p><script>alert(1)</script><a href="javascript:alert(1)">bad</a><a href="https://example.com">ok</a>')
        ->save();

    $html = $this->get('/id/activities/grid-zero-training-day')->assertSuccessful()->getContent();

    expect($html)->toContain('Halo')->toContain('example.com')
        ->not->toContain('<script>alert')->not->toContain('onclick')->not->toContain('javascript:');
});

it('keeps this site\'s inline images but strips external and scripted ones', function (): void {
    $html = Html::sanitize(
        '<p>a</p><img src="/storage/publications/ok.jpg" alt="ok">'
        .'<img src="https://evil.example/x.png">'
        .'<img src="//evil.example/y.png">'
        .'<img src="/storage/z.jpg" onerror="alert(1)">'
        .'<img src="javascript:alert(1)">',
    );

    expect($html)->toContain('/storage/publications/ok.jpg')
        ->not->toContain('evil.example')
        ->not->toContain('onerror')
        ->not->toContain('javascript:');
});
