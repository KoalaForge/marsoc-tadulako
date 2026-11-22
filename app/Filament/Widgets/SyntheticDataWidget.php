<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\MediaItem;
use App\Models\Personnel;
use App\Models\Publication;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class SyntheticDataWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $count = collect([Personnel::class, Activity::class, MediaItem::class, Publication::class])
            ->sum(fn (string $model): int => $model::query()->where('is_synthetic', true)->count());

        return [
            Stat::make('Data demo', $count)
                ->description($count > 0 ? 'Hapus dengan: php artisan content:purge-synthetic --force' : 'Tidak ada data demo tersisa')
                ->color($count > 0 ? 'warning' : 'success'),
        ];
    }
}
