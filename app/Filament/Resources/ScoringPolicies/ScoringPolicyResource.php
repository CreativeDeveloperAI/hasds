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
    protected static ?string $navigationLabel = 'سياسات وأوزان التنقيط';
    protected static ?string $pluralModelLabel = 'سياسات التقييم المعيارية';
    protected static ?string $modelLabel = 'معيار تنقيط';
    protected static string|null|\UnitEnum $navigationGroup = 'إدارة النظام والسياق';
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
