<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublishedFlag;
use App\Models\Concerns\HasSyntheticFlag;
use App\Models\Concerns\InteractsWithSiteMedia;
use App\Models\Concerns\TouchesCacheVersion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

/**
 * Roster record. `callsign` is the display identity and cross-reference
 * target (activities.related_member_callsigns, publications.author_callsign);
 * `callsign_lower` is the indexed, case-insensitive route-binding key.
 */
final class Personnel extends Model implements HasMedia
{
    use HasPublishedFlag;
    use HasSyntheticFlag;
    use HasTranslations;
    use InteractsWithSiteMedia;
    use TouchesCacheVersion;

    protected $connection = 'mongodb';

    protected $collection = 'personnel';

    /** @var list<string> */
    protected $fillable = [
        'callsign',
        'callsign_lower',
        'member_id',
        'role',
        'status',
        'joined_on',
        'era_id',
        'impression_focus',
        'primary_platform',
        'secondary_platform',
        'grid',
        'bio',
        'sort',
        'is_published',
        'is_synthetic',
        'portrait_consent',
    ];

    /** @var list<string> */
    public array $translatable = [
        'role',
        'impression_focus',
        'bio',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $personnel): void {
            $personnel->callsign_lower = mb_strtolower((string) $personnel->callsign);
        });
    }

    protected function casts(): array
    {
        return [
            'sort' => 'integer',
            'portrait_consent' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'callsign_lower';
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where('callsign_lower', mb_strtolower((string) $value))->first();
    }

    /**
     * @return BelongsTo<Era, $this>
     */
    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portrait')->singleFile();
    }
}
