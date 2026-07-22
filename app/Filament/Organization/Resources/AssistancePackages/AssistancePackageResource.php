<?php

namespace App\Filament\Organization\Resources\AssistancePackages;

use App\Filament\Organization\Resources\AssistancePackages\Pages\CreateAssistancePackage;
use App\Filament\Organization\Resources\AssistancePackages\Pages\EditAssistancePackage;
use App\Filament\Organization\Resources\AssistancePackages\Pages\ListAssistancePackages;
use App\Filament\Organization\Resources\AssistancePackages\Pages\ViewAssistancePackage;
use App\Filament\Organization\Resources\AssistancePackages\RelationManagers\DistributionsRelationManager;
use App\Filament\Organization\Resources\AssistancePackages\Schemas\AssistancePackageForm;
use App\Filament\Organization\Resources\AssistancePackages\Tables\AssistancePackagesTable;
use App\Models\AssistancePackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssistancePackageResource extends Resource
{
    protected static ?string $model = AssistancePackage::class;

    public static function getNavigationLabel(): string
    {
        return __('messages.resource_144e69dc');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.resource_f45a3b3a');
    }

    public static function getModelLabel(): string
    {
        return __('messages.resource_e9a36844');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.resource_1264f55c');
    }

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    public static function form(Schema $schema): Schema
    {
        return AssistancePackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssistancePackagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DistributionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssistancePackages::route('/'),
            'create' => CreateAssistancePackage::route('/create'),
            'edit' => EditAssistancePackage::route('/{record}/edit'),
            'view' => ViewAssistancePackage::route('/{record}'),
        ];
    }
}
