<?php

declare(strict_types=1);

use App\Filament\Resources\Activities\Pages\CreateActivity;
use App\Filament\Resources\Personnels\Pages\CreatePersonnel;
use App\Filament\Resources\Publications\Pages\CreatePublication;
use App\Models\ActivityCategory;
use App\Models\Era;
use App\Models\Personnel;
use App\Models\PublicationCategory;
use App\Models\User;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Livewire\Livewire;

use function Filament\Support\apply_search_constraint;

/*
 * Filament 5's stock apply_search_constraint() emits whereRaw("col like ?
 * escape '!'"), which laravel-mongodb returns verbatim: a raw SQL string ends
 * up inside the BSON filter and MongoDB rejects the query ("foreach() argument
 * must be of type array|object, string given" in the driver's compileWheres).
 * scripts/patch-filament-mongo-search.php (composer post-autoload-dump, and an
 * explicit step + guard in docker/Dockerfile) rewrites the helper to a native
 * ->where($column, 'like', $pattern). These tests fail on unpatched vendor code.
 */

beforeEach(function (): void {
    $this->actingAs(User::create([
        'name' => 'Admin',
        'email' => 'search-'.Str::random(8).'@mrt.test',
        'password' => bcrypt('password'),
        'is_admin' => true,
    ]));
});

it('compiles Filament search constraints to native Mongo clauses, not raw SQL', function (): void {
    $query = Personnel::query()->where('is_published', true);

    $query->where(function ($nested): void {
        apply_search_constraint($nested, 'callsign', '%zz_no_match_zz%');
    });

    $filter = $query->toBase()->toMql()['find'][0] ?? null;

    expect($filter)->toBeArray();

    $assertNoRawStrings = function (array $clause) use (&$assertNoRawStrings): void {
        foreach (['$and', '$or', '$nor'] as $operator) {
            foreach ($clause[$operator] ?? [] as $entry) {
                expect($entry)->toBeArray();
                $assertNoRawStrings($entry);
            }
        }
    };

    $assertNoRawStrings($filter);

    // The actual bug class: executing it must not throw.
    expect($query->count())->toBe(0);
});

it('searches and lists relationship options on the Personnel create form (era select)', function (): void {
    Era::create(['key' => 'search-era', 'name' => 'Search Era', 'uniform' => ['en' => 'x', 'id' => 'x'], 'gear' => ['en' => 'x', 'id' => 'x'], 'helmet' => ['en' => 'x', 'id' => 'x']]);

    $component = Livewire::test(CreatePersonnel::class)->assertSuccessful();

    /** @var Select $select */
    $select = $component->instance()->getSchema('form')->getComponent('era_id');

    expect($select->getSearchResults('search'))->not->toBeEmpty()
        ->and($select->getSearchResults('zz_no_match_zz'))->toBe([]);
});

it('searches relationship options on the Activity and Publication create forms (category selects)', function (): void {
    ActivityCategory::create(['slug' => 'search-act', 'name' => ['en' => 'Search Act', 'id' => 'Cari Act']]);
    PublicationCategory::create(['slug' => 'search-pub', 'name' => ['en' => 'Search Pub', 'id' => 'Cari Pub']]);

    foreach ([[CreateActivity::class, 'activity_category_id', 'search-act'], [CreatePublication::class, 'publication_category_id', 'search-pub']] as [$page, $field, $term]) {
        /** @var Select $select */
        $select = Livewire::test($page)->assertSuccessful()->instance()->getSchema('form')->getComponent($field);

        expect($select->getSearchResults($term))->not->toBeEmpty()
            ->and($select->getSearchResults('zz_no_match_zz'))->toBe([]);
    }
});
