<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BeneficiariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('national_id')
                    ->label('رقم الهوية')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('الاسم الكامل')
                    ->searchable(),
                // جلب البيانات من الجدول الوسيط (Pivot) الخاص بالجمعية الحالية
                TextColumn::make('organizations.pivot.phone_number')
                    ->label('رقم التواصل'),
                TextColumn::make('organizations.pivot.family_members_count')
                    ->label('عدد الأفراد')
                    ->sortable(),
                TextColumn::make('organizations.pivot.priority_score')
                    ->label('مؤشر الأولوية الذكي (AI)')
                    ->badge()
                    ->color(fn ($state) => $state >= 75 ? 'danger' : ($state >= 40 ? 'warning' : 'success'))
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('organizations.pivot.is_displaced')
                    ->label('حالة النزوح'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
