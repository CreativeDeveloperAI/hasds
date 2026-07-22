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
                    ->label(__('messages.ui_aa3b4b79'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('license_number')
                    ->label(__('messages.ui_476367b4'))
                    ->placeholder(__('messages.ui_9ecc4e30'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('primary_contact_person')
                    ->label(__('messages.ui_847f7948'))
                    ->searchable(),
                IconColumn::make('enable_cross_checking')
                    ->label(__('messages.ui_396bfa24'))
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label(__('messages.ui_4e979f82'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('messages.ui_c7a79f3c'))
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('messages.ui_fda97965'))
                    ->options(OrganizationStatus::class),
            ])
            ->recordActions([
                EditAction::make()->label(__('messages.ui_1a3770b6')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
