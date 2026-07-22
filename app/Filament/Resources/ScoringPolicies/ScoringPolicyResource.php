<?php

namespace App\Filament\Resources\ScoringPolicies;

use App\Filament\Resources\ScoringPolicies\Pages\CreateScoringPolicy;
use App\Filament\Resources\ScoringPolicies\Pages\EditScoringPolicy;
use App\Filament\Resources\ScoringPolicies\Pages\ListScoringPolicies;
use App\Filament\Resources\ScoringPolicies\Schemas\ScoringPolicyForm;
use App\Filament\Resources\ScoringPolicies\Tables\ScoringPoliciesTable;
use App\Models\ScoringPolicie;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ScoringPolicyResource extends Resource
{
    protected static ?string $model = ScoringPolicie::class;

    public static function getNavigationLabel(): string
    {
        return __('messages.resource_cf107b3f');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.resource_9df75372');
    }

    public static function getModelLabel(): string
    {
        return __('messages.resource_4fe7a6b7');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.resource_2780f940');
    }

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    public static function form(Schema $schema): Schema
    {
        return ScoringPolicyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScoringPoliciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScoringPolicies::route('/'),
            'create' => CreateScoringPolicy::route('/create'),
            'edit' => EditScoringPolicy::route('/{record}/edit'),
        ];
    }
}
