<?php

namespace App\Filament\Resources\ScoringPolicies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScoringPoliciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('policy_name')
                    ->label(__('messages.ui_158a6a53'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('policy_key')
                    ->label(__('messages.ui_cc3da773'))
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category')
                    ->label(__('messages.ui_3e01d944'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'social' => 'info',
                        'health' => 'danger',
                        'shelter' => 'warning',
                        'financial' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'social' => __('messages.ui_869f4ae0'),
                        'health' => __('messages.ui_f394891f'),
                        'shelter' => __('messages.ui_1a5d2bd0'),
                        'financial' => __('messages.ui_23148558'),
                        default => $state,
                    }),
                TextColumn::make('points_weight')
                    ->label(__('messages.ui_9cb2f7b7'))
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label(__('messages.ui_596fa1d4'))
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label(__('messages.ui_38530a2a')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
