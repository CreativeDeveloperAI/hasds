<?php

namespace App\Filament\Resources\ScoringPolicies\Pages;

use App\Filament\Resources\ScoringPolicies\ScoringPolicyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScoringPolicy extends EditRecord
{
    protected static string $resource = ScoringPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
