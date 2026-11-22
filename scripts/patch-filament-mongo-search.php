<?php

declare(strict_types=1);

/**
 * Filament 5's apply_search_constraint() helper always uses whereRaw(), which
 * mongodb/laravel-mongodb cannot compile into a BSON filter — it leaks the raw
 * SQL string into the filter array instead, crashing every default
 * ->searchable() column the moment a user types a search term. No stable
 * release of either package fixes this yet, so this patches the installed
 * vendor file on every composer install/update (wired via post-autoload-dump)
 * until upstream does. Idempotent: no-ops if already patched or if upstream
 * changes the function (in which case this prints a warning instead of
 * silently doing nothing forever).
 */
$path = __DIR__.'/../vendor/filament/support/src/helpers.php';

if (! is_file($path)) {
    fwrite(STDERR, "patch-filament-mongo-search: {$path} not found, skipping.\n");

    exit(0);
}

$contents = file_get_contents($path);

$original = <<<'PHP'
        function apply_search_constraint(Builder $query, string | Expression $column, string $pattern, string $boolean = 'and', bool $isInverse = false): Builder
        {
            $column = $query->getQuery()->getGrammar()->wrap($column);
            $operator = $isInverse ? 'not like' : 'like';

            return $query->whereRaw("{$column} {$operator} ? escape '!'", [$pattern], $boolean);
        }
    PHP;

$replacement = <<<'PHP'
        function apply_search_constraint(Builder $query, string | Expression $column, string $pattern, string $boolean = 'and', bool $isInverse = false): Builder
        {
            $operator = $isInverse ? 'not like' : 'like';

            // Patched for MongoDB compatibility — see scripts/patch-filament-mongo-search.php.
            if ($column instanceof Expression) {
                $wrapped = $query->getQuery()->getGrammar()->wrap($column);

                return $query->whereRaw("{$wrapped} {$operator} ? escape '!'", [$pattern], $boolean);
            }

            return $query->where($column, $operator, $pattern, $boolean);
        }
    PHP;

if (str_contains($contents, $replacement)) {
    exit(0);
}

if (! str_contains($contents, $original)) {
    fwrite(STDERR, "patch-filament-mongo-search: apply_search_constraint() body didn't match the expected original — Filament may have changed it. Re-check the patch.\n");

    exit(1);
}

file_put_contents($path, str_replace($original, $replacement, $contents));

fwrite(STDOUT, "patch-filament-mongo-search: patched apply_search_constraint() for MongoDB compatibility.\n");
