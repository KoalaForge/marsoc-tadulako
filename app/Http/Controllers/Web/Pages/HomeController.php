<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Era;
use App\Models\MediaItem;
use App\Models\WeaponVariant;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $latest = Activity::query()->where('is_published', true)->orderByDesc('created_at')->limit(3)->get();
        $featured = MediaItem::query()->where('is_published', true)->orderByDesc('created_at')->orderBy('sort')->limit(12)->get()
            ->filter(fn (MediaItem $m): bool => $m->getMedia('photos')->isNotEmpty())
            ->take(4)
            ->values();

        $blocks = PageContent::blocks('home');

        return Inertia::render('Home', [
            'blocks' => $blocks,
            'latestActivities' => Presenter::activities($latest),
            'featuredMedia' => $featured->map(fn (MediaItem $m): array => Presenter::mediaItem($m))->all(),
            'teaserImage' => $blocks['impression-teaser']['image'] ?? Presenter::firstPhotoUrl([
                ...WeaponVariant::query()->with('media')->where('is_published', true)->orderBy('sort')->limit(20)->get()->all(),
                ...Era::query()->with('media')->where('is_published', true)->orderBy('sort')->limit(20)->get()->all(),
            ]),
        ]);
    }
}
