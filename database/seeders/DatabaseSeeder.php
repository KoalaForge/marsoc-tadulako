<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Content only. Admin users are created with `php artisan mrt:create-admin`,
 * never seeded.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PageBlockSeeder::class,
            SiteSettingsSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
