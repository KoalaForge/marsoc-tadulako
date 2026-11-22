<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class ShowController extends Controller
{
    public function __invoke(string $locale, string $slug): Response
    {
        $activity = Activity::query()->where('slug', $slug)->where('is_published', true)->first();

        abort_if($activity === null, 404);

        $callsigns = array_map(
            'strtolower',
            (array) json_decode((string) json_encode($activity->related_member_callsigns), true),
        );
        $members = Personnel::query()->where('is_published', true)->orderBy('sort')->get()
            ->filter(fn (Personnel $p): bool => in_array($p->callsign_lower, $callsigns, strict: true));

        $photo = MediaItem::query()
            ->where('related_activity_slug', $activity->slug)
            ->where('is_published', true)
            ->first();

        return Inertia::render('Activities/Show', [
            'activity' => Presenter::activity($activity),
            'relatedMembers' => Presenter::members($members),
            'relatedPhoto' => $photo === null ? null : Presenter::mediaItem($photo),
        ]);
    }
}
