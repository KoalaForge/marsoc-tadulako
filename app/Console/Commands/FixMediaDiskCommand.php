<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;

/**
 * One-off repair for media uploaded through the admin before the upload
 * component was pinned to the public disk: those files (and their
 * conversions) sit on the private "local" disk, so the site and the admin
 * preview cannot serve them. Moves each affected media directory to the
 * medialibrary disk and updates its row. Idempotent: rows already on the
 * target disk are skipped.
 */
final class FixMediaDiskCommand extends Command
{
    protected $signature = 'media:fix-disk {--dry-run : List what would move without touching anything}';

    protected $description = 'Move media stored on the wrong (private) disk to the medialibrary disk';

    public function handle(): int
    {
        $target = (string) config('media-library.disk_name');
        $dryRun = (bool) $this->option('dry-run');
        $moved = 0;

        Media::query()->where('disk', '!=', $target)->each(function (Media $media) use ($target, $dryRun, &$moved): void {
            $from = Storage::disk($media->disk);
            $to = Storage::disk($target);
            $directory = PathGeneratorFactory::create($media)->getPath($media);
            $files = $from->allFiles($directory);

            $this->line(sprintf('#%s %s: %s -> %s (%d files)', $media->getKey(), $media->file_name, $media->disk, $target, count($files)));

            if ($dryRun) {
                return;
            }

            foreach ($files as $file) {
                if (! $to->exists($file)) {
                    $stream = $from->readStream($file);
                    $to->writeStream($file, $stream, ['visibility' => 'public']);

                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }
            }

            $media->disk = $target;

            if (filled($media->conversions_disk) && $media->conversions_disk !== $target) {
                $media->conversions_disk = $target;
            }

            $media->saveQuietly();

            $from->deleteDirectory($directory);
            $moved++;
        });

        $this->info($dryRun ? 'Dry run complete.' : "Moved {$moved} media item(s) to the '{$target}' disk.");

        return self::SUCCESS;
    }
}
