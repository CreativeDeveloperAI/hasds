<?php

namespace App\Filament\Resources\ScoringPolicies\Pages;

use App\Filament\Resources\ScoringPolicies\ScoringPolicyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScoringPolicies extends ListRecords
{
    protected static string $resource = ScoringPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
