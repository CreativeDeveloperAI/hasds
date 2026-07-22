<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PolicyCategory: string implements HasLabel
{
    case Social = 'social';
    case Health = 'health';
    case Shelter = 'shelter';
    case Financial = 'financial';

    /**
     * إرجاع النص العربي لكل تصنيف ليتم عرضه تلقائياً في شاشات الفرز والمدخلات
     */
    public function getLabel(): ?string
    {
        return __('enums.PolicyCategory.'.$this->value);
    }
}
