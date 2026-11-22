<?php

declare(strict_types=1);

namespace App\Filament\Resources\Personnels\Schemas;

use App\Enums\PersonnelStatus;
use App\Filament\Shared\Schemas\LocaleTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonnelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->schema([
                        TextInput::make('callsign')
                            ->label('Callsign')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Diisi sekali saat anggota diterima — jarang diubah.'),
                        TextInput::make('member_id')
                            ->label('Member ID'),
                        Select::make('status')
                            ->label('Status')
                            ->options(PersonnelStatus::class)
                            ->required()
                            ->default(PersonnelStatus::Active->value)
                            ->native(false),
                        TextInput::make('joined_on')
                            ->label('Bergabung (YYYY-MM)')
                            ->placeholder('2025-01')
                            ->helperText('Presisi bulan, bukan tanggal — sesuai data identity book.'),
                        TextInput::make('grid')
                            ->label('Grid Reference')
                            ->placeholder('00.89°S 119.87°E'),
                        SpatieMediaLibraryFileUpload::make('portrait')
                            ->label('Portrait')
                            ->collection('portrait')
                            ->image()
                            ->imageEditor(),
                    ])
                    ->columns(2),

                Section::make('Impresi')
                    ->schema([
                        Select::make('era_id')
                            ->label('Era Impresi')
                            ->relationship('era', 'key')
                            ->searchable()
                            ->native(false),
                        TextInput::make('primary_platform')
                            ->label('Platform Primer')
                            ->helperText('String bebas — tidak selalu cocok persis dengan nama Weapon Variant.'),
                        TextInput::make('secondary_platform')
                            ->label('Platform Sekunder')
                            ->helperText('Kosongkan jika tidak ada (jangan isi tanda "—").'),
                    ])
                    ->columns(2),

                LocaleTabs::make(fn (string $locale): array => [
                    TextInput::make("role.{$locale}")
                        ->label("Peran ({$locale})")
                        ->required($locale === 'id'),
                    TextInput::make("impression_focus.{$locale}")
                        ->label("Fokus Impresi ({$locale})"),
                    Textarea::make("bio.{$locale}")
                        ->label("Bio ({$locale})")
                        ->rows(3)
                        ->required($locale === 'id'),
                ], label: 'Konten'),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->default(true),
            ]);
    }
}
