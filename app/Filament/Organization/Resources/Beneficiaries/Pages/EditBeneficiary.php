<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Pages;

use App\Filament\Organization\Resources\Beneficiaries\BeneficiaryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeneficiary extends EditRecord
{
    protected static string $resource = BeneficiaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
