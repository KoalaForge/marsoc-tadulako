<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $latest = Activity::query()->where('is_published', true)->orderByDesc('occurred_on')->limit(3)->get();
        $featured = MediaItem::query()->where('is_published', true)->orderBy('sort')->limit(12)->get()
            ->filter(fn (MediaItem $m): bool => $m->getMedia('photos')->isNotEmpty())
            ->take(4)
            ->values();

        return Inertia::render('Home', [
            'blocks' => PageContent::blocks('home'),
            'latestActivities' => Presenter::activities($latest),
            'featuredMedia' => $featured->map(fn (MediaItem $m): array => Presenter::mediaItem($m))->all(),
            'teaserImage' => Presenter::relativeUrl((string) MediaItem::query()->where('key', 'eq-mk18-urgi')->first()?->getFirstMediaUrl('photos', 'wide')),
        ]);
    }
}
