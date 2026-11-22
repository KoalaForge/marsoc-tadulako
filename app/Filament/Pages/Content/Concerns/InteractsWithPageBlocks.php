<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content\Concerns;

use App\Models\PageBlock;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

/**
 * Shared mount/save lifecycle for the 8 page-content editors. Each
 * editor's form() still hand-writes its own Sections per block, so the
 * admin screen reads as "Beranda -> Formula Identitas -> ...", not
 * generic "Section -> Item" vocabulary — this trait only owns loading a
 * page's PageBlock documents into $data keyed by block suffix, and
 * writing edited fields back on save(), one document at a time (no
 * multi-document transaction exists on standalone Mongo to need one).
 */
trait InteractsWithPageBlocks
{
    /** @var array<string, mixed> */
    public array $data = [];

    abstract public static function pageKey(): string;

    /**
     * Livewire does not carry protected properties across requests, so
     * blocks are re-queried in both mount() and save().
     *
     * @return Collection<string, PageBlock> keyed by block suffix
     */
    private function loadBlocks(): Collection
    {
        $prefix = static::pageKey().'.';

        return PageBlock::query()
            ->where('page_key', static::pageKey())
            ->get()
            ->keyBy(fn (PageBlock $block): string => str_starts_with($block->block_key, $prefix)
                ? substr($block->block_key, strlen($prefix))
                : $block->block_key);
    }

    public function mount(): void
    {
        $this->form->fill(
            $this->loadBlocks()->mapWithKeys(fn (PageBlock $block, string $suffix): array => [
                $suffix => [
                    'eyebrow' => $this->blockTranslations($block, 'eyebrow'),
                    'heading' => $this->blockTranslations($block, 'heading'),
                    'subheading' => $this->blockTranslations($block, 'subheading'),
                    'body' => $this->blockTranslations($block, 'body'),
                    'footnote' => $this->blockTranslations($block, 'footnote'),
                    'items' => $block->items ?? [],
                ],
            ])->all(),
        );
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $blocks = $this->loadBlocks();

        foreach ($state as $suffix => $attributes) {
            $blocks->get($suffix)?->fill($attributes)->save();
        }

        Notification::make()
            ->title('Perubahan disimpan')
            ->success()
            ->send();
    }

    /**
     * @return array<string, string>
     */
    private function blockTranslations(PageBlock $block, string $key): array
    {
        $raw = $block->getTranslations($key);

        return collect(config('mrt.locales', ['id', 'en']))
            ->mapWithKeys(fn (string $locale): array => [$locale => $raw[$locale] ?? ''])
            ->all();
    }
}
