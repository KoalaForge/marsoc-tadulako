<?php

declare(strict_types=1);

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Allow-list sanitizer for admin-authored rich text, applied on output so
 * stored HTML is never trusted by the public site (v-html). Only the tags
 * the Filament rich editor's restricted toolbar can produce are allowed;
 * links are limited to http(s)/mailto and forced to open safely.
 */
final class Html
{
    private static ?HtmlSanitizer $sanitizer = null;

    public static function sanitize(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return self::sanitizer()->sanitize($html);
    }

    private static function sanitizer(): HtmlSanitizer
    {
        return self::$sanitizer ??= new HtmlSanitizer(
            (new HtmlSanitizerConfig)
                ->allowElement('p')
                ->allowElement('br')
                ->allowElement('strong')
                ->allowElement('em')
                ->allowElement('u')
                ->allowElement('s')
                ->allowElement('h2')
                ->allowElement('h3')
                ->allowElement('ul')
                ->allowElement('ol')
                ->allowElement('li')
                ->allowElement('blockquote')
                ->allowElement('img', ['src', 'alt', 'width', 'height'])
                ->allowElement('a', ['href'])
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                // Images: this site's own uploads only (relative /storage/... or
                // our own host) — no hotlinking third-party images.
                ->allowRelativeMedias()
                ->allowMediaSchemes(['https', 'http'])
                ->allowMediaHosts(array_filter([parse_url((string) config('app.url'), PHP_URL_HOST)]))
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
                ->forceAttribute('a', 'target', '_blank'),
        );
    }
}
