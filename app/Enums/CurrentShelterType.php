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
        return match ($this) {
            self::Tent => 'خيمة قماشية / عازل نايلون بمخيمات النزوح',
            self::ShelterCenter => 'مركز إيواء جماعي (مدرسة، مشفى، مرفق عام)',
            self::RentApartment => 'شقة مستأجرة (توزيع نفقات سكن)',
            self::HostFamily => 'مستضاف لدى أقارب / أصدقاء بالمنزل',
            self::Home => 'المنزل الأصلي للمواطن (غير مدمر بالكامل)',
        };
    }
}
