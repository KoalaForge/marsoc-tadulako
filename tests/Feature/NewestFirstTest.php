<?php

declare(strict_types=1);

use App\Filament\Resources\Activities\Pages\ListActivities;
use App\Filament\Resources\MediaItems\Pages\ListMediaItems;
use App\Filament\Resources\Personnels\Pages\ListPersonnels;
use App\Filament\Resources\Publications\Pages\ListPublications;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use App\Models\User;
use App\Support\CachedProps;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('invalidates cached page props with content:cache-bust (run on every deploy)', function (): void {
    expect(CachedProps::remember('bust-test', fn (): int => 1))->toBe(1)
        // Cached: the resolver is not called again, so a deploy that changed the
        // ordering would keep serving the old props...
        ->and(CachedProps::remember('bust-test', fn (): int => 2))->toBe(1);

    $this->artisan('content:cache-bust')->assertSuccessful();

    // ...until the version is bumped.
    expect(CachedProps::remember('bust-test', fn (): int => 3))->toBe(3);
});

it('opens every admin resource table newest-created first', function (): void {
    $this->actingAs(User::create(['name' => 'A', 'email' => Str::random(8).'@mrt.test', 'password' => bcrypt('x'), 'is_admin' => true]));

    $make = function (string $model, Closure $attributes): array {
        $records = [];

        foreach ([['old', 30], ['new', 1], ['mid', 10]] as [$name, $daysAgo]) {
            $record = $model::create($attributes($name));
            $record->created_at = now()->subDays($daysAgo);
            $record->save();
            $records[$name] = $record;
        }

        return [$records['new'], $records['mid'], $records['old']];
    };

    $tables = [
        [ListMediaItems::class, $make(MediaItem::class, fn (string $n): array => ['key' => "k-{$n}", 'category' => 'equipment-photography', 'type' => 'photo', 'title' => ['id' => $n, 'en' => $n]])],
        [ListActivities::class, $make(Activity::class, fn (string $n): array => ['slug' => "a-{$n}", 'title' => ['id' => $n, 'en' => $n], 'occurred_on' => now(), 'overview' => ['id' => 'x', 'en' => 'x']])],
        [ListPublications::class, $make(Publication::class, fn (string $n): array => ['slug' => "p-{$n}", 'title' => ['id' => $n, 'en' => $n], 'excerpt' => ['id' => 'x', 'en' => 'x'], 'content' => ['id' => '<p>x</p>', 'en' => '<p>x</p>']])],
        [ListPersonnels::class, $make(Personnel::class, fn (string $n): array => ['callsign' => strtoupper("c-{$n}"), 'role' => ['id' => 'r', 'en' => 'r'], 'status' => 'active', 'bio' => ['id' => 'b', 'en' => 'b']])],
    ];

    foreach ($tables as [$page, $expected]) {
        Livewire::test($page)->loadTable()->assertCanSeeTableRecords($expected, inOrder: true);
    }
});
