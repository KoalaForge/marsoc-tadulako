<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Media;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Support\CachedProps;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Media/Index', [
            'blocks' => PageContent::blocks('media'),
            'items' => CachedProps::remember('media.index', fn (): array => MediaItem::query()
                ->where('is_published', true)->orderByDesc('created_at')->orderBy('sort')->get()
                ->map(fn (MediaItem $m): array => Presenter::mediaItem($m))->values()->all()),
            ...Presenter::mediaCategories(),
        ]);
    }
}
