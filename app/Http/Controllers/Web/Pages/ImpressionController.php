<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages;

use App\Http\Controllers\Controller;
use App\Support\CachedProps;
use App\Support\PageContent;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class ImpressionController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Impression', [
            'blocks' => PageContent::blocks('impression'),
            'eras' => CachedProps::remember('impression.eras', fn (): array => Presenter::eras()),
            'weaponCategories' => CachedProps::remember('impression.weapons', fn (): array => Presenter::weaponCategories()),
        ]);
    }
}
