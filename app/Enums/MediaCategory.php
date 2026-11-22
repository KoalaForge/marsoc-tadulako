<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Slug-backed on purpose — the frontend's original data file stored the
 * English display label as the key, which breaks the moment that label is
 * edited. The stored value here is always the slug; labels are resolved
 * separately per locale (see getLabel() — Indonesian, the app default).
 */
enum MediaCategory: string implements HasLabel
{
    case EquipmentPhotography = 'equipment-photography';
    case FieldActivities = 'field-activities';
    case TrainingDocumentation = 'training-documentation';
    case SkirmishDocumentation = 'skirmish-documentation';
    case MilsimEvents = 'milsim-events';
    case TeamPhotography = 'team-photography';
    case IndividualPortraits = 'individual-portraits';
    case HistoricalImpression = 'historical-impression';

    public function getLabel(): string
    {
        return match ($this) {
            self::EquipmentPhotography => 'Foto Equipment',
            self::FieldActivities => 'Kegiatan Lapangan',
            self::TrainingDocumentation => 'Dokumentasi Latihan',
            self::SkirmishDocumentation => 'Dokumentasi Skirmish',
            self::MilsimEvents => 'Kegiatan MilSim',
            self::TeamPhotography => 'Foto Tim',
            self::IndividualPortraits => 'Potret Individu',
            self::HistoricalImpression => 'Impresi Historis',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::EquipmentPhotography => 'Equipment Photography',
            self::FieldActivities => 'Field Activities',
            self::TrainingDocumentation => 'Training Documentation',
            self::SkirmishDocumentation => 'Skirmish Documentation',
            self::MilsimEvents => 'MilSim Events',
            self::TeamPhotography => 'Team Photography',
            self::IndividualPortraits => 'Individual Portraits',
            self::HistoricalImpression => 'Historical Impression',
        };
    }
}
