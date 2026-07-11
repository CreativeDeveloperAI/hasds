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
                    ->label('المعيار المعتمَد')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('policy_key')
                    ->label('المفتاح المرجعي')
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category')
                    ->label('نوع المؤشر')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'social' => 'info',
                        'health' => 'danger',
                        'shelter' => 'warning',
                        'financial' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'social' => 'اجتماعي',
                        'health' => 'صحي/طبي',
                        'shelter' => 'مأوى ونزوح',
                        'financial' => 'مادي واقتصادي',
                        default => $state,
                    }),
                TextColumn::make('points_weight')
                    ->label('وزن النقاط المضافة')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label('نشط برمجياً')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('تعديل نقاط الوزن'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
