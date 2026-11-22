<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Media;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class ShowController extends Controller
{
    public function __invoke(string $locale, string $key): Response
    {
        $item = MediaItem::query()->where('key', $key)->where('is_published', true)->first();

        abort_if($item === null, 404);

        $activity = $item->related_activity_slug === null ? null : Activity::query()
            ->where('slug', $item->related_activity_slug)->where('is_published', true)->first();

        return Inertia::render('Media/Show', [
            'item' => Presenter::mediaItem($item),
            'relatedActivity' => $activity === null ? null : Presenter::activity($activity),
            'categoryLabels' => Presenter::mediaCategories()['categoryLabels'],
        ]);
    }
}
