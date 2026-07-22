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
                    ->label(__('messages.ui_0a375e0f'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label(__('messages.ui_90f9115a'))
                    ->searchable(),
                // جلب البيانات من الجدول الوسيط (Pivot) الخاص بالجمعية الحالية
                TextColumn::make('organizations.pivot.phone_number')
                    ->label(__('messages.ui_38ca5539')),
                TextColumn::make('organizations.pivot.family_members_count')
                    ->label(__('messages.ui_d6ac7e16'))
                    ->sortable(),
                TextColumn::make('organizations.pivot.priority_score')
                    ->label(__('messages.ui_092d2588'))
                    ->badge()
                    ->color(fn ($state) => $state >= 75 ? 'danger' : ($state >= 40 ? 'warning' : 'success'))
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('organizations.pivot.is_displaced')
                    ->label(__('messages.ui_21b1a335')),
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
