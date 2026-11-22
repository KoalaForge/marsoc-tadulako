<?php

declare(strict_types=1);

use App\Filament\Resources\Activities\Pages\EditActivity;
use App\Filament\Resources\MediaItems\Pages\EditMediaItem;
use App\Filament\Resources\Publications\Pages\EditPublication;
use App\Filament\Shared\RelationManagers\PhotosRelationManager;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Era;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use App\Models\PublicationCategory;
use App\Models\User;
use App\Models\WeaponVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

function adminUser(): User
{
    return User::create([
        'name' => 'Test Admin',
        'email' => 'test-admin-'.Str::random(12).'@mrt.test',
        'password' => bcrypt('password'),
        'is_admin' => true,
    ]);
}

it('redirects guests away from every admin resource index', function (string $path): void {
    $this->get("/admin/{$path}")->assertRedirect('/admin/login');
})->with([
    'activities', 'activity-categories', 'eras', 'media-items',
    'personnels', 'publication-categories', 'publications', 'weapon-variants',
]);

it('lets an authenticated admin view every resource index page', function (string $path): void {
    $this->actingAs(adminUser());

    $this->get("/admin/{$path}")->assertSuccessful();
})->with([
    'activities', 'activity-categories', 'eras', 'media-items',
    'personnels', 'publication-categories', 'publications', 'weapon-variants',
]);

it('lets an authenticated admin view every resource create page', function (string $path): void {
    $this->actingAs(adminUser());

    $this->get("/admin/{$path}/create")->assertSuccessful();
})->with([
    'activities', 'activity-categories', 'eras', 'media-items',
    'personnels', 'publication-categories', 'publications', 'weapon-variants',
]);

it('lets an authenticated admin view every resource edit page', function (): void {
    $this->actingAs(adminUser());

    // Suffixed with a random token rather than relying solely on
    // inter-test truncation timing — keeps this test correct even if a
    // prior case's cleanup hasn't settled yet.
    $suffix = Str::random(8);

    $category = ActivityCategory::create(['slug' => "test-cat-{$suffix}", 'name' => ['en' => 'Test', 'id' => 'Uji']]);
    $pubCategory = PublicationCategory::create(['slug' => "test-pub-cat-{$suffix}", 'name' => ['en' => 'Test', 'id' => 'Uji']]);
    $era = Era::create(['key' => "test-era-{$suffix}", 'name' => 'Test Era', 'uniform' => ['en' => 'x', 'id' => 'x'], 'gear' => ['en' => 'x', 'id' => 'x'], 'helmet' => ['en' => 'x', 'id' => 'x']]);

    $personnel = Personnel::create(['callsign' => "EDIT-{$suffix}", 'role' => ['en' => 'x', 'id' => 'x'], 'bio' => ['en' => 'x', 'id' => 'x']]);
    $activity = Activity::create(['slug' => "edit-activity-{$suffix}", 'title' => ['en' => 'x', 'id' => 'x'], 'occurred_on' => now(), 'overview' => ['en' => 'x', 'id' => 'x'], 'activity_category_id' => $category->getKey()]);
    $mediaItem = MediaItem::create(['key' => "edit-media-{$suffix}", 'category' => 'equipment-photography', 'type' => 'photo', 'title' => ['en' => 'x', 'id' => 'x']]);
    $publication = Publication::create(['slug' => "edit-pub-{$suffix}", 'title' => ['en' => 'x', 'id' => 'x'], 'excerpt' => ['en' => 'x', 'id' => 'x'], 'body' => ['en' => 'x', 'id' => 'x'], 'publication_category_id' => $pubCategory->getKey()]);
    $weapon = WeaponVariant::create(['key' => "edit-weapon-{$suffix}", 'category' => 'rifle', 'name' => 'Test Rifle']);

    // Each resource's {record} binds via the model's own getRouteKeyName(),
    // not the Mongo _id — matching the public-site convention of
    // human-readable, stable keys.
    $this->get("/admin/activity-categories/{$category->slug}/edit")->assertSuccessful();
    $this->get("/admin/publication-categories/{$pubCategory->slug}/edit")->assertSuccessful();
    $this->get("/admin/eras/{$era->key}/edit")->assertSuccessful();
    $this->get("/admin/personnels/{$personnel->callsign_lower}/edit")->assertSuccessful();
    $this->get("/admin/activities/{$activity->slug}/edit")->assertSuccessful();
    $this->get("/admin/media-items/{$mediaItem->key}/edit")->assertSuccessful();
    $this->get("/admin/publications/{$publication->slug}/edit")->assertSuccessful();
    $this->get("/admin/weapon-variants/{$weapon->key}/edit")->assertSuccessful();
});

it('loads the photos relation manager table with real media rows', function (): void {
    $this->actingAs(adminUser());

    $item = MediaItem::create(['key' => 'rm-'.Str::random(6), 'category' => 'equipment-photography', 'type' => 'photo', 'title' => ['en' => 'x', 'id' => 'x']]);
    $item->addMedia(database_path('seeders/images/media-demo/training-day-1.jpg'))->preservingOriginal()->toMediaCollection('photos');

    Livewire::test(PhotosRelationManager::class, [
        'ownerRecord' => $item,
        'pageClass' => EditMediaItem::class,
    ])->assertSuccessful()->loadTable()->assertCanSeeTableRecords($item->getMedia('photos'));
});

it('uploads a photo through the media item form and a banner through the activity form', function (): void {
    $this->actingAs(adminUser());
    Storage::fake('public');

    $item = MediaItem::create(['key' => 'up-'.Str::random(6), 'category' => 'equipment-photography', 'type' => 'photo', 'title' => ['en' => 'x', 'id' => 'x']]);

    Livewire::test(EditMediaItem::class, ['record' => $item->key])
        ->fillForm(['photos' => [UploadedFile::fake()->image('a.jpg', 1200, 800)]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($item->fresh()->getMedia('photos'))->toHaveCount(1);

    $activity = Activity::create(['slug' => 'ban-'.Str::random(6), 'title' => ['en' => 'x', 'id' => 'x'], 'occurred_on' => now(), 'overview' => ['en' => 'x', 'id' => 'x']]);

    Livewire::test(EditActivity::class, ['record' => $activity->slug])
        ->fillForm(['banner' => UploadedFile::fake()->image('b.jpg', 1600, 900)])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($activity->fresh()->getMedia('banner'))->toHaveCount(1);
});

it('uploads a banner through the publication form', function (): void {
    $this->actingAs(adminUser());
    Storage::fake('public');

    $category = PublicationCategory::create(['slug' => 'pc-'.Str::random(6), 'name' => ['en' => 'T', 'id' => 'T']]);
    $publication = Publication::create([
        'slug' => 'pub-'.Str::random(6), 'title' => ['en' => 'x', 'id' => 'x'], 'excerpt' => ['en' => 'x', 'id' => 'x'],
        'content' => ['en' => '<p>x</p>', 'id' => '<p>x</p>'], 'publication_category_id' => $category->getKey(),
    ]);

    Livewire::test(EditPublication::class, ['record' => $publication->slug])
        ->fillForm(['cover' => UploadedFile::fake()->image('c.jpg', 1600, 900)])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($publication->fresh()->getMedia('cover'))->toHaveCount(1);
});
