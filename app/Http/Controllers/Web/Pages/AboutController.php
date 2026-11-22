<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages;

use App\Http\Controllers\Controller;
use App\Support\PageContent;
use Inertia\Inertia;
use Inertia\Response;

final class AboutController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('About', [
            'blocks' => PageContent::blocks('about'),
        ]);
    }
}
