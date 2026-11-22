<?php

declare(strict_types=1);

use App\Filament\Resources\Activities\Pages\EditActivity;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

/*
 * Filament's media upload defaults to FILESYSTEM_DISK ("local" = private) and
 * previews through signed temporary URLs, which 404 for the browser and never
 * work for the public site. AppServiceProvider pins it to MEDIA_DISK ("public").
 */

function makeActivity(): Activity
{
    return Activity::create([
        'slug' => 'disk-'.Str::random(6),
        'title' => ['en' => 'x', 'id' => 'x'],
        'occurred_on' => now(),
        'overview' => ['en' => 'x', 'id' => 'x'],
    ]);
}

it('stores admin uploads on the public disk with a plain public URL', function (): void {
    Storage::fake('public');
    Storage::fake('local');

    $this->actingAs(User::create([
        'name' => 'A', 'email' => 'disk-'.Str::random(8).'@mrt.test', 'password' => bcrypt('x'), 'is_admin' => true,
    ]));

    $activity = makeActivity();

    Livewire::test(EditActivity::class, ['record' => $activity->slug])
        ->fillForm(['banner' => UploadedFile::fake()->image('b.jpg', 1600, 900)])
        ->call('save')
        ->assertHasNoFormErrors();

    $media = $activity->fresh()->getFirstMedia('banner');

    expect($media->disk)->toBe('public')
        ->and(Storage::disk('public')->exists($media->getPathRelativeToRoot()))->toBeTrue()
        ->and(Storage::disk('local')->allFiles())->toBe([])
        ->and($media->getUrl())->not->toContain('signature=');
});

it('moves media that was stored on the private disk to the public disk', function (): void {
    Storage::fake('public');
    Storage::fake('local');

    $activity = makeActivity();
    $media = $activity->addMedia(database_path('seeders/images/media-demo/training-day-1.jpg'))
        ->preservingOriginal()
        ->toMediaCollection('banner', 'local');

    $path = $media->getPathRelativeToRoot();
    expect($media->disk)->toBe('local')
        ->and(Storage::disk('local')->exists($path))->toBeTrue();

    $this->artisan('media:fix-disk --dry-run')->assertSuccessful();
    expect($media->fresh()->disk)->toBe('local');

    $this->artisan('media:fix-disk')->assertSuccessful();

    $media = $media->fresh();
    expect($media->disk)->toBe('public')
        ->and(Storage::disk('public')->exists($path))->toBeTrue()
        ->and(Storage::disk('local')->exists($path))->toBeFalse();

    // Idempotent: a second run has nothing left to move.
    $this->artisan('media:fix-disk')->expectsOutputToContain('Moved 0')->assertSuccessful();
});
