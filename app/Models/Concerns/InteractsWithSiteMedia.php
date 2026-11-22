<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Named conversions shared by every media-bearing model. `thumb` is
 * non-queued (needed instantly in the Filament table); everything else is
 * queued (see QUEUE_CONNECTION — requires a running worker).
 *
 * Every conversion is scoped to raster collections via
 * `->performOnCollections(...)` so the brand SVG logo slots on
 * SiteSettings never enter the conversion pipeline — running a webp
 * encoder over an SVG is at best wasted work, at worst a corrupt
 * derivative.
 */
trait InteractsWithSiteMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $rasterCollections = ['photos', 'portrait', 'cover', 'og_image', 'banner', 'image'];

        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 400, 400)
            ->format('webp')
            ->quality(75)
            ->nonQueued()
            ->performOnCollections(...$rasterCollections);

        $this->addMediaConversion('tile')
            ->fit(Fit::Crop, 600, 750)
            ->format('webp')
            ->quality(80)
            ->performOnCollections('photos');

        $this->addMediaConversion('card')
            ->fit(Fit::Contain, 800, 600)
            ->format('webp')
            ->quality(80)
            ->performOnCollections('photos', 'cover', 'banner', 'image');

        $this->addMediaConversion('wide')
            ->fit(Fit::Crop, 1600, 900)
            ->format('webp')
            ->quality(82)
            ->performOnCollections('photos', 'cover', 'og_image', 'banner', 'image');

        $this->addMediaConversion('slider')
            ->fit(Fit::Contain, 1600, 1200)
            ->format('webp')
            ->quality(85)
            ->performOnCollections('photos');

        $this->addMediaConversion('zoom')
            ->fit(Fit::Max, 2400, 2400)
            ->format('webp')
            ->quality(85)
            ->performOnCollections('photos');

        $this->addMediaConversion('portrait')
            ->fit(Fit::Crop, 600, 800)
            ->format('webp')
            ->quality(85)
            ->performOnCollections('portrait');
    }
}
