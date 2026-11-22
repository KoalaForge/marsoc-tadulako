<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\TouchesCacheVersion;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Editorial taxonomy for publications. Kept separate from
 * ActivityCategory rather than one shared "kind"-discriminated taxonomy —
 * the two sets never cross-reference each other and never need listing
 * together.
 */
final class PublicationCategory extends Model
{
    use HasPublishedFlag;
    use HasTranslations;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'publication_categories';

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
     * @return HasMany<Publication, $this>
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
