<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DisabilityType: string implements HasLabel
{
    case Physical = 'physical';
    case Visual = 'visual';
    case Hearing = 'hearing';
    case Mental = 'mental';
    case Sensory = 'sensory';
    case Multiple = 'multiple';

    /**
     * تحديد نوع الإعاقة لتقديم المساعدات النوعية (كراسي متحركة، سماعات، نظارات)
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Physical => 'إعاقة حركية / جسدية',
            self::Visual => 'إعاقة بصرية / كف بصر',
            self::Hearing => 'إعاقة سمعية / صمم',
            self::Mental => 'إعاقة ذهنية / عقلية',
            self::Sensory => 'إعاقة نطق وتخاطب / حسية',
            self::Multiple => 'إعاقات متعددة مركبة',
        };
    }
}
