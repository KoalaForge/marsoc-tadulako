<?php

declare(strict_types=1);

namespace App\Filament\Pages\Settings;

use App\Enums\NavigationGroup;
use App\Filament\Shared\Schemas\LocaleTabs;
use App\Models\SiteSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Components\Attributes\Unexposed;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Editor for the SiteSettings singleton. Not a Resource: there is exactly
 * one document, so no list/create/delete screens are wanted.
 *
 * @property-read Schema $form
 */
final class SiteSettingsPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Pengaturan;

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?string $slug = 'settings/site';

    protected string $view = 'filament-panels::pages.page';

    /** @var array<string, mixed> */
    public array $data = [];

    private const LOGO_SLOTS = [
        'logo_color' => 'Logo — Master / Color',
        'logo_gold_on_black' => 'Logo — Gold on Black',
        'logo_gold_transparent' => 'Logo — Gold Transparent',
        'logo_white_on_black' => 'Logo — White on Black',
        'logo_white_transparent' => 'Logo — White Transparent',
        'logo_black_transparent' => 'Logo — Black Transparent',
    ];

    private function record(): SiteSettings
    {
        return SiteSettings::query()->firstOrCreate([]);
    }

    public function mount(): void
    {
        $record = $this->record();

        $data = $record->only(['whatsapp_number', 'callsign_short', 'tagline', 'home_grid_readout', 'copyright_holder', 'copyright_start_year']);

        foreach (['whatsapp_message_heading', 'community_name', 'disclaimer', 'default_seo_title', 'default_seo_description'] as $field) {
            $raw = $record->getTranslations($field);
            $data[$field] = ['id' => $raw['id'] ?? '', 'en' => $raw['en'] ?? ''];
        }

        $this->form->fill($data);
    }

    #[Unexposed]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->model($this->record())
            ->statePath('data')
            ->components([
                Section::make('WhatsApp')
                    ->schema([
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->helperText('Format internasional tanpa + atau spasi, mis. 6285155429967.')
                            ->regex('/^\d{8,15}$/')
                            ->required(),
                    ]),
                Section::make('Identitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('callsign_short')->label('Callsign singkat'),
                        TextInput::make('tagline')->label('Tagline'),
                        TextInput::make('home_grid_readout')->label('Grid readout beranda'),
                        TextInput::make('copyright_holder')->label('Pemegang hak cipta'),
                        TextInput::make('copyright_start_year')->label('Tahun mulai hak cipta')->numeric(),
                    ]),
                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("community_name.{$locale}")->label("Nama komunitas ({$locale})")->required($locale === 'id'),
                    TextInput::make("whatsapp_message_heading.{$locale}")->label("Judul pesan WhatsApp ({$locale})"),
                    Textarea::make("disclaimer.{$locale}")
                        ->label("Disclaimer non-afiliasi ({$locale})")
                        ->helperText('Gunakan {strong} untuk bagian yang ditebalkan (nama komunitas).')
                        ->rows(4)
                        ->required($locale === 'id'),
                    TextInput::make("default_seo_title.{$locale}")->label("SEO title default ({$locale})"),
                    Textarea::make("default_seo_description.{$locale}")->label("SEO description default ({$locale})")->rows(2),
                ], label: 'Teks'),
                Section::make('Logo')
                    ->collapsible()
                    ->columns(3)
                    ->schema(array_map(
                        fn (string $collection, string $label) => SpatieMediaLibraryFileUpload::make($collection)
                            ->label($label)
                            ->collection($collection)
                            ->image(),
                        array_keys(self::LOGO_SLOTS),
                        self::LOGO_SLOTS,
                    )),
            ]);
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
                        Action::make('save')->label('Simpan')->submit('save')->keyBindings(['mod+s']),
                    ])->sticky(),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // fill() routes translatable maps to setTranslations(); media
        // uploads are persisted by the form components themselves.
        $record = $this->record();
        $record->fill($data)->save();
        $this->form->model($record)->saveRelationships();

        Notification::make()->title('Pengaturan disimpan')->success()->send();
    }
}
