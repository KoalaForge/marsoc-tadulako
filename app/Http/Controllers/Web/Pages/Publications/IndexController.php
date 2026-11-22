<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Publications;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Support\CachedProps;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Publications/Index', [
            'blocks' => PageContent::blocks('publications'),
            'publications' => CachedProps::remember('publications.index', fn (): array => Publication::query()
                ->where('is_published', true)->orderByDesc('published_on')->get()
                ->map(fn (Publication $p): array => Presenter::publication($p))->values()->all()),
        ]);
    }
}
