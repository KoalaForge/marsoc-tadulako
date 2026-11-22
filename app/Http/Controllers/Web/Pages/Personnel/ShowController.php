<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Pages\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Era;
use App\Models\Personnel;
use App\Support\Presenter;
use Inertia\Inertia;
use Inertia\Response;

final class ShowController extends Controller
{
    public function __invoke(string $locale, string $callsign): Response
    {
        $member = Personnel::query()
            ->where('callsign_lower', mb_strtolower($callsign))
            ->where('is_published', true)
            ->first();

        abort_if($member === null, 404);

        $activities = Activity::query()
            ->where('is_published', true)
            ->where('related_member_callsigns', $member->callsign)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Personnel/Show', [
            'member' => Presenter::member($member, Era::query()->find($member->era_id)),
            'relatedActivities' => Presenter::activities($activities),
        ]);
    }
}
