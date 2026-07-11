<?php

namespace App\Filament\Organization\Resources\AssistancePackages\Tables;

use App\Enums\AssistancePackageStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                    ->label('اسم الحزمة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('package_type')
                    ->label('النوع')
                    ->badge(),
                TextColumn::make('total_quantity')
                    ->label('الكمية الإجمالية')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('distributed_quantity')
                    ->label('الموزع فعلياً')
                    ->color('success')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('الحالة الحالية')
                    ->badge(),
                TextColumn::make('start_date')
                    ->label('تاريخ البدء')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('حالة الحزم')
                    ->options([
                        'active' => 'النشطة حالياً',
                        'completed' => 'المكتملة والمنتهية',
                        'paused' => 'الموقوفة مؤقتاً',
                    ]),
            ])
            ->recordActions([
                // إظهار إجراء التعديل فقط عندما تكون الحزمة ليست مكتملة، وعكس ذلك يتم إجباره على عرض السجل فقط
                EditAction::make()
                    ->label('تعديل الحزمة')
                    ->visible(fn ($record) => $record?->status !== AssistancePackageStatus::Completed),
                ViewAction::make()
                    ->label('عرض تفاصيل الحزمة')
                    ->visible(fn ($record) => $record?->status === AssistancePackageStatus::Completed),            ])
            ;
    }
}
