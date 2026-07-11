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
    protected static ?string $navigationLabel = 'إدارة وتوزيع المساعدات';
    protected static ?string $pluralModelLabel = 'حزم المساعدات الإغاثية';
    protected static ?string $modelLabel = 'حزمة مساعدة';
    protected static string|null|\UnitEnum $navigationGroup = 'العمليات الميدانية';
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
