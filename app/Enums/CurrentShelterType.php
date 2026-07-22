<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CurrentShelterType: string implements HasLabel
{
    case Tent = 'tent';
    case ShelterCenter = 'shelter_center';
    case RentApartment = 'rent_apartment';
    case HostFamily = 'host_family';
    case Home = 'home';

    /**
     * إرجاع المسمى العربي لنوع المأوى الحالي للنازح أو المقيم
     */
    public function getLabel(): ?string
    {
        return __('enums.CurrentShelterType.'.$this->value);
    }
}
