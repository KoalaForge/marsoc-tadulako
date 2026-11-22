<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\TouchesCacheVersion;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Editorial taxonomy + filter-chip order for activities. A collection,
 * not an enum: names are translatable and a non-developer will plausibly
 * add a category the frontend's hardcoded set never anticipated.
 */
final class ActivityCategory extends Model
{
    use HasPublishedFlag;
    use HasTranslations;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'activity_categories';

    /** @var list<string> */
    protected $fillable = [
        'slug',
        'name',
        'sort',
        'is_published',
    ];

    /** @var list<string> */
    public array $translatable = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'sort' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasMany<Activity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
