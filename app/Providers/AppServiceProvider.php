<?php

namespace App\Providers;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Filament's media upload picks its disk from FILESYSTEM_DISK ("local",
        // the PRIVATE disk) and then previews through signed temporary URLs —
        // neither the public site nor the browser can use those. Every
        // medialibrary upload in this app is public site content, so pin the
        // component to the medialibrary's own disk (MEDIA_DISK, "public") with
        // public visibility: plain /storage/... URLs served straight by Caddy.
        SpatieMediaLibraryFileUpload::configureUsing(
            fn (SpatieMediaLibraryFileUpload $upload): SpatieMediaLibraryFileUpload => $upload
                ->disk((string) config('media-library.disk_name'))
                ->visibility('public'),
        );
    }
}
