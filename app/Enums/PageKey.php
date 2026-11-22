<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The route set is a closed list — adding a page is a deploy, not an
 * admin action, so this is a backed enum rather than a free-text field.
 * `_global` is a pseudo-page for content shared across pages (the
 * disclaimer), addressed by block_key alone.
 */
enum PageKey: string
{
    case Global = '_global';
    case Home = 'home';
    case About = 'about';
    case Personnel = 'personnel';
    case Impression = 'impression';
    case Activities = 'activities';
    case Media = 'media';
    case Publications = 'publications';
    case Join = 'join';
}
