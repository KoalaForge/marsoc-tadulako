<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Support\CachedProps;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Activities/Index', [
            'blocks' => PageContent::blocks('activities'),
            'activities' => CachedProps::remember('activities.index', fn (): array => Presenter::activities(
                Activity::query()->where('is_published', true)->orderByDesc('occurred_on')->get(),
            )),
        ]);
    }
}
