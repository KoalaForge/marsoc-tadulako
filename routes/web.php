<?php

declare(strict_types=1);

use App\Enums\WebRoute;
use App\Http\Controllers\Web\LocaleRedirectController;
use App\Http\Controllers\Web\Pages\AboutController;
use App\Http\Controllers\Web\Pages\Activities\IndexController as ActivitiesIndexController;
use App\Http\Controllers\Web\Pages\Activities\ShowController as ActivitiesShowController;
use App\Http\Controllers\Web\Pages\HomeController;
use App\Http\Controllers\Web\Pages\ImpressionController;
use App\Http\Controllers\Web\Pages\JoinController;
use App\Http\Controllers\Web\Pages\Media\IndexController as MediaIndexController;
use App\Http\Controllers\Web\Pages\Media\ShowController as MediaShowController;
use App\Http\Controllers\Web\Pages\Personnel\IndexController as PersonnelIndexController;
use App\Http\Controllers\Web\Pages\Personnel\ShowController as PersonnelShowController;
use App\Http\Controllers\Web\Pages\Publications\IndexController as PublicationsIndexController;
use App\Http\Controllers\Web\Pages\Publications\ShowController as PublicationsShowController;
use App\Http\Controllers\Web\RobotsController;
use App\Http\Controllers\Web\SitemapController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class);
Route::get('/sitemap.xml', SitemapController::class);
Route::get('/', LocaleRedirectController::class)->name(WebRoute::Root->value);

Route::prefix('{locale}')
    ->where(['locale' => 'id|en'])
    ->middleware(SetLocale::class)
    ->group(function (): void {
        Route::get('/', HomeController::class)->name(WebRoute::Home->value);
        Route::get('/about', AboutController::class)->name(WebRoute::About->value);

        Route::get('/personnel', PersonnelIndexController::class)->name(WebRoute::PersonnelIndex->value);
        Route::get('/personnel/{callsign}', PersonnelShowController::class)->name(WebRoute::PersonnelShow->value);

        Route::get('/impression', ImpressionController::class)->name(WebRoute::Impression->value);

        Route::get('/activities', ActivitiesIndexController::class)->name(WebRoute::ActivitiesIndex->value);
        Route::get('/activities/{slug}', ActivitiesShowController::class)->name(WebRoute::ActivitiesShow->value);

        Route::get('/media', MediaIndexController::class)->name(WebRoute::MediaIndex->value);
        Route::get('/media/{key}', MediaShowController::class)->name(WebRoute::MediaShow->value);

        Route::get('/publications', PublicationsIndexController::class)->name(WebRoute::PublicationsIndex->value);
        Route::get('/publications/{slug}', PublicationsShowController::class)->name(WebRoute::PublicationsShow->value);

        Route::get('/join', JoinController::class)->name(WebRoute::JoinIndex->value);
    });
