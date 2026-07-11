<?php

namespace App\Filament\Resources\Organizations\Tables;

use App\Enums\OrganizationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrganizationsTable
{
    /**
     * إعداد وتصميم جدول استعراض المؤسسات الشريكة
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('المؤسسة الشريكة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('license_number')
                    ->label('رقم الترخيص')
                    ->placeholder('مبادرة محلية غير مرخصة')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('primary_contact_person')
                    ->label('المنسق المسؤول')
                    ->searchable(),
                IconColumn::make('enable_cross_checking')
                    ->label('منع التكرار')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('الحالة الحالية')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('تاريخ الانضمام')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('تصفية حسب الحالة المعيارية')
                    ->options(OrganizationStatus::class),
            ])
            ->recordActions([
                EditAction::make()->label('إدارة وترخيص'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
