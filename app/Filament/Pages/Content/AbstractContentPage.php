<?php

declare(strict_types=1);

namespace App\Filament\Pages\Content;

use App\Enums\NavigationGroup;
use App\Filament\Pages\Content\Concerns\InteractsWithPageBlocks;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Components\Attributes\Unexposed;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Base for the page-content editors. A subclass declares its page_key and
 * hand-writes `sections()` — one Section per block on that page.
 *
 * @property-read Schema $form
 */
abstract class AbstractContentPage extends Page
{
    use InteractsWithPageBlocks;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::KontenHalaman;

    protected string $view = 'filament-panels::pages.page';

    /**
     * @return array<Component>
     */
    abstract protected function sections(): array;

    #[Unexposed]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components($this->sections())
            ->statePath('data');
    }

    #[Unexposed]
    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Simpan')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }
}
