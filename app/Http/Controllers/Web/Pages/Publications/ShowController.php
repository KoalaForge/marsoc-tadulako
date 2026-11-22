<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Publications;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class ShowController extends Controller
{
    public function __invoke(string $locale, string $slug): Response
    {
        $publication = Publication::query()->where('slug', $slug)->where('is_published', true)->first();

        abort_if($publication === null, 404);

        return Inertia::render('Publications/Show', [
            'publication' => Presenter::publication($publication),
        ]);
    }
}
