<?php

declare(strict_types=1);

use App\Support\Localize;

it('picks the requested locale from a bilingual map', function (): void {
    expect(Localize::pick(['id' => 'Halo', 'en' => 'Hello'], 'en'))->toBe('Hello')
        ->and(Localize::pick(['id' => 'Halo', 'en' => 'Hello'], 'id'))->toBe('Halo');
});

it('falls back to English, then to the first value, when the locale is missing', function (): void {
    expect(Localize::pick(['en' => 'Hello'], 'id'))->toBe('Hello')
        ->and(Localize::pick(['fr' => 'Bonjour'], 'id'))->toBe('Bonjour');
});

it('passes plain strings and null through unchanged', function (): void {
    expect(Localize::pick('M.R.T', 'en'))->toBe('M.R.T')
        ->and(Localize::pick(null, 'en'))->toBeNull();
});
