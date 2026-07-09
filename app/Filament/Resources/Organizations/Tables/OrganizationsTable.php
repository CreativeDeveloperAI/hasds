<?php

namespace App\Filament\Resources\Organizations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
             TextColumn::make('name')
                    ->label('اسم المؤسسة')
                    ->searchable()
                    ->sortable(),
             TextColumn::make('license_number')
                    ->label('رقم الترخيص')
                    ->placeholder('مبادرة غير مرخصة')
                    ->searchable(),
             TextColumn::make('primary_contact_person')
                    ->label(' المسؤول')
                    ->searchable(),
             IconColumn::make('enable_cross_checking')
                    ->label('منع التكرار')
                    ->boolean(),

                // عرض حالة الحساب بألوان مميزة لسهولة القراءة والفرز اللحظي
             TextColumn::make('status')
                    ->label('حالة الحساب')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'مفعّل',
                        'pending' => 'قيد الانتظار',
                        'suspended' => 'موقوف',
                        default => $state,
                    }),
             TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                // فلتر سريع لعرض المؤسسات حسب حالتها (مثلاً عرض طلبات الانتظار فقط)
                SelectFilter::make('status')
                    ->label('تصفية حسب الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مفعّل',
                        'suspended' => 'موقوف',
                    ]),
            ])
            ->recordActions([
               EditAction::make()->label('تعديل والحالة'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
