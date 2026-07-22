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
        return __('enums.DisabilityType.'.$this->value);
    }
}
