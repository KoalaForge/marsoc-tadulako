<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Named routes for the public, locale-prefixed site. The Impression page
 * is a single document with an in-page lightbox — there are no
 * era/gear/specialist detail routes, unlike the mrt reference app.
 */
enum WebRoute: string
{
    case Root = 'web.root';
    case Home = 'web.home';
    case About = 'web.about';
    case PersonnelIndex = 'web.personnel.index';
    case PersonnelShow = 'web.personnel.show';
    case Impression = 'web.impression';
    case ActivitiesIndex = 'web.activities.index';
    case ActivitiesShow = 'web.activities.show';
    case MediaIndex = 'web.media.index';
    case MediaShow = 'web.media.show';
    case PublicationsIndex = 'web.publications.index';
    case PublicationsShow = 'web.publications.show';
    case JoinIndex = 'web.join.index';
}
