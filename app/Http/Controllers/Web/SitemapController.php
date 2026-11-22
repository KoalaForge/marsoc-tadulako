<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use Illuminate\Http\Response;

/**
 * One <url> per page per locale, each carrying xhtml:link hreflang
 * alternates for every locale — published records only.
 */
final class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $paths = ['', '/about', '/personnel', '/impression', '/activities', '/media', '/publications', '/join'];

        $published = fn (string $model, string $field, string $prefix): array => $model::query()
            ->where('is_published', true)->pluck($field)
            ->map(fn (string $value): string => "{$prefix}/".rawurlencode($value))->all();

        $paths = [
            ...$paths,
            ...$published(Personnel::class, 'callsign_lower', '/personnel'),
            ...$published(Activity::class, 'slug', '/activities'),
            ...$published(MediaItem::class, 'key', '/media'),
            ...$published(Publication::class, 'slug', '/publications'),
        ];

        $locales = config('mrt.locales', ['id', 'en']);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($paths as $path) {
            foreach ($locales as $locale) {
                $xml .= '  <url><loc>'.e(url("/{$locale}{$path}")).'</loc>';
                foreach ($locales as $alt) {
                    $xml .= '<xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e(url("/{$alt}{$path}")).'"/>';
                }
                $xml .= "</url>\n";
            }
        }

        return response($xml.'</urlset>'."\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
