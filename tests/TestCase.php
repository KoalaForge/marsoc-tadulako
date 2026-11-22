<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private static bool $migrated = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (! self::$migrated) {
            $this->artisan('migrate', ['--force' => true])->run();
            $this->artisan('migrate', [
                '--database' => 'sqlite_media',
                '--path' => 'database/migrations/media',
                '--force' => true,
            ])->run();

            self::$migrated = true;
        }
    }
}
