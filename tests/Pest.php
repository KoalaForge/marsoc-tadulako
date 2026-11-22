<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| No RefreshDatabase: standalone MongoDB has no transactions, so a
| rollback-per-test trait would silently do nothing and leave state
| between tests. truncateMongo() below is the replacement.
*/

pest()->extend(TestCase::class)->in('Feature', 'Unit');

/**
 * Deletes every document from every collection on the mongodb
 * connection. Enumerated from the live database via
 * listCollectionNames() rather than a hand-written per-model list, so
 * it can't drift the moment a new model is added.
 *
 * The str_contains($db, 'test') guard is non-negotiable — a
 * misconfigured .env.testing would otherwise wipe production.
 */
function truncateMongo(): void
{
    $db = (string) config('database.connections.mongodb.database');

    if (! str_contains($db, 'test')) {
        throw new RuntimeException("Refusing to truncate non-test database [{$db}].");
    }

    $connection = DB::connection('mongodb');

    foreach ($connection->getDatabase()->listCollectionNames() as $name) {
        $connection->getDatabase()->selectCollection($name)->deleteMany([]);
    }
}

// pest()->beforeEach, not a bare beforeEach(): a bare top-level hook in
// Pest.php is NOT applied to the test files, which silently left rows
// from earlier tests in the database.
pest()->beforeEach(function (): void {
    truncateMongo();
});
