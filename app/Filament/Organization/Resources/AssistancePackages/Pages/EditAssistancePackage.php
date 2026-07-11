<?php

namespace App\Filament\Organization\Resources\AssistancePackages\Pages;

use App\Filament\Organization\Resources\AssistancePackages\AssistancePackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssistancePackage extends EditRecord
{
    protected static string $resource = AssistancePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
