<?php

namespace App\Filament\Organization\Resources\Beneficiaries;

use App\Filament\Organization\Resources\Beneficiaries\Pages\CreateBeneficiary;
use App\Filament\Organization\Resources\Beneficiaries\Pages\EditBeneficiary;
use App\Filament\Organization\Resources\Beneficiaries\Pages\ListBeneficiaries;
use App\Filament\Organization\Resources\Beneficiaries\Schemas\BeneficiaryForm;
use App\Filament\Organization\Resources\Beneficiaries\Tables\BeneficiariesTable;
use App\Models\Beneficiary;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BeneficiaryResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'organizations';
    protected static ?string $model = Beneficiary::class;

    protected static ?string $navigationLabel = 'إدارة المستفيدين';
    protected static ?string $pluralModelLabel = 'المستفيدين';
    protected static ?string $modelLabel = 'مستفيد';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    /**
     * تعديل الاستعلام الأساسي (Query) ليعرض للمؤسسة فقط المستفيدين المرتبطين بها
     */
    public static function getEloquentQuery(): Builder
    {
        $tenantId = Filament::getTenant()?->id;
        return parent::getEloquentQuery()->whereHas('organizations', function ($query) use ($tenantId) {
            $query->where('organization_id', $tenantId);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return BeneficiaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BeneficiariesTable::configure($table);
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
            'index' => ListBeneficiaries::route('/'),
            'create' => CreateBeneficiary::route('/create'),
            'edit' => EditBeneficiary::route('/{record}/edit'),
        ];
    }
}
