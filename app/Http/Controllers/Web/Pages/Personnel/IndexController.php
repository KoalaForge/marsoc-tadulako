<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Support\CachedProps;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Personnel/Index', [
            'blocks' => PageContent::blocks('personnel'),
            'members' => CachedProps::remember('personnel.index', fn (): array => Presenter::members(
                Personnel::query()->where('is_published', true)->orderBy('sort')->get(),
            )),
        ]);
    }
}
