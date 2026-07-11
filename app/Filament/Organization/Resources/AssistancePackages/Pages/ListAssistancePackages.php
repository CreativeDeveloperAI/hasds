<?php

namespace App\Filament\Organization\Resources\AssistancePackages\Pages;

use App\Filament\Organization\Resources\AssistancePackages\AssistancePackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssistancePackages extends ListRecords
{
    protected static string $resource = AssistancePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
