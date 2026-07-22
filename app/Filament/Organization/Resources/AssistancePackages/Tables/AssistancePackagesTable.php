<?php

namespace App\Filament\Organization\Resources\AssistancePackages\Tables;

use App\Enums\AssistancePackageStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssistancePackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('messages.ui_a0f1ffa3'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('package_type')
                    ->label(__('messages.ui_a70b0172'))
                    ->badge(),
                TextColumn::make('total_quantity')
                    ->label(__('messages.ui_fa77e76e'))
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('distributed_quantity')
                    ->label(__('messages.ui_9d84cc5b'))
                    ->color('success')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label(__('messages.ui_4e979f82'))
                    ->badge(),
                TextColumn::make('start_date')
                    ->label(__('messages.ui_afb19bc7'))
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('messages.ui_61e3ea06'))
                    ->options([
                        'active' => __('messages.ui_66398ba8'),
                        'completed' => __('messages.ui_a7439b8f'),
                        'paused' => __('messages.ui_0d6cb6c2'),
                    ]),
            ])
            ->recordActions([
                // إظهار إجراء التعديل فقط عندما تكون الحزمة ليست مكتملة، وعكس ذلك يتم إجباره على عرض السجل فقط
                EditAction::make()
                    ->label(__('messages.ui_94d8b9b0'))
                    ->visible(fn ($record) => $record?->status !== AssistancePackageStatus::Completed),
                ViewAction::make()
                    ->label(__('messages.ui_e432e3a8'))
                    ->visible(fn ($record) => $record?->status === AssistancePackageStatus::Completed),            ]);
    }
}
