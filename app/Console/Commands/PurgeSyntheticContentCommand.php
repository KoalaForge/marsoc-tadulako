<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use Illuminate\Console\Command;

/**
 * The exit ramp for the prototype/demo data the site launched with:
 * removes every record flagged is_synthetic (and, via model deletion,
 * their media files). Real-steel equipment reference photos are NOT
 * flagged synthetic, so they survive. Dry run unless --force.
 */
final class PurgeSyntheticContentCommand extends Command
{
    protected $signature = 'content:purge-synthetic {--force : Actually delete (default is a dry run)}';

    protected $description = 'Delete all demo/synthetic content records (dry run unless --force)';

    public function handle(): int
    {
        $models = [
            'Personel' => Personnel::class,
            'Kegiatan' => Activity::class,
            'Media' => MediaItem::class,
            'Publikasi' => Publication::class,
        ];

        $total = 0;

        foreach ($models as $label => $model) {
            $records = $model::query()->where('is_synthetic', true)->get();
            $total += $records->count();
            $this->line(sprintf('%-10s %d', $label, $records->count()));

            if ($this->option('force')) {
                // One by one: model deletion removes media files and bumps the cache version.
                $records->each->delete();
            }
        }

        $this->option('force')
            ? $this->info("Deleted {$total} synthetic records.")
            : $this->warn("Dry run: {$total} records would be deleted. Re-run with --force.");

        return self::SUCCESS;
    }
}
