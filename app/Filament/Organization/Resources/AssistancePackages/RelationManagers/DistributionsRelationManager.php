<?php

namespace App\Filament\Organization\Resources\AssistancePackages\RelationManagers;

use App\Enums\AssistancePackageStatus;
use App\Enums\DistributionStatus;
use App\Models\AssistanceDistribution;
use App\Models\Beneficiary;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DistributionsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributions';

    public function table(Table $table): Table
    {
        $package = $this->getOwnerRecord();
        $isPackageCompleted = $package->status === AssistancePackageStatus::Completed;

        return $table
            ->heading(__('messages.resource_788fbdf3'))
            ->modelLabel(__('messages.resource_ac1fe346'))
            ->pluralModelLabel(__('messages.resource_b90270a3'))
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('beneficiary.full_name')
                    ->label(__('messages.ui_382c547d'))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('beneficiary.national_id')
                    ->label(__('messages.ui_3ca30c31'))
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('distribution_status')
                    ->label(__('messages.ui_b6f2dd7d'))
                    ->badge(), // يقرأ التسميات والألوان تلقائياً من الـ Enum المربوطة

                TextColumn::make('delivered_at')
                    ->label(__('messages.ui_87dda0b4'))
                    ->dateTime('Y-m-d H:i')
                    ->placeholder(__('messages.ui_b8724632'))
                    ->sortable(),

                TextColumn::make('notes')
                    ->label(__('messages.ui_bdbd9f4c'))
                    ->placeholder(__('messages.ui_8fe7b57e'))
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('distribution_status')
                    ->label(__('messages.ui_b0dd5616'))
                    ->options(DistributionStatus::class),
            ])
            ->headerActions([
                // إخفاء زر الإضافة في حالتين:
                // 1. إذا اكتملت الحصص المرصودة (المستفيدين الفعليين >= total_quantity)
                // 2. إذا تم إنهاء وإغلاق الدورة الإغاثية بالكامل
                CreateAction::make()
                    ->label(__('messages.ui_c505cd65'))
                    ->modalHeading(__('messages.ui_6f23cc38'))
                    ->visible(function () use ($package, $isPackageCompleted) {
                        if ($isPackageCompleted) {
                            return false;
                        }

                        $assignedCount = AssistanceDistribution::where('assistance_package_id', $package->id)->count();

                        return $assignedCount < $package->total_quantity;
                    })
                    ->schema([
                        Select::make('beneficiary_id')
                            ->label(__('messages.ui_e9869ad8'))
                            ->options(function () use ($package) {
                                $tenantId = Filament::getTenant()?->id;
                                $alreadyAssigned = AssistanceDistribution::where('assistance_package_id', $package->id)
                                    ->pluck('beneficiary_id');

                                return Beneficiary::whereHas('organizations', function ($q) use ($tenantId) {
                                    $q->where('organization_id', $tenantId);
                                })
                                    ->whereNotIn('id', $alreadyAssigned)
                                    ->pluck('full_name', 'id');
                            })
                            ->searchable()
                            ->required(),
                        Hidden::make('organization_id')
                            ->default(fn () => Filament::getTenant()?->id),
                        Hidden::make('distribution_status')
                            ->default(DistributionStatus::Pending->value),
                        Textarea::make('notes')
                            ->label(__('messages.ui_5f16b8ae'))
                            ->rows(2)
                            ->required(),
                    ])
                    ->after(function () use ($package) {
                        // عند تسجيل فرد يدوي وتجاوز العدد المتاح للمخزن يتم زيادة العدد الكلي تلقائياً
                        $package->increment('total_quantity');
                    }),
            ])
            ->recordActions([
                // 1. إجراء التسليم الفوري (يظهر فقط للحزم النشطة ولغير المستلمين)
                Action::make('mark_delivered')
                    ->label(__('messages.ui_a6f4655f'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => ! $isPackageCompleted && $record->distribution_status === DistributionStatus::Pending)
                    ->action(function ($record) use ($package) {
                        $record->update([
                            'distribution_status' => DistributionStatus::Delivered,
                            'delivered_at' => now(),
                        ]);

                        // تحديث تلقائي لكمية التوزيع الفعلي بداخل الحزمة الأساسية
                        $package->increment('distributed_quantity');

                        Notification::make()
                            ->title(__('messages.ui_05097766'))
                            ->success()
                            ->send();
                    }),

                // 2. إجراء الاستبدال الذكي (يُخفى في حال التسليم الفعلي أو إغلاق الحزمة بالكامل)
                Action::make('replace_beneficiary')
                    ->label(__('messages.ui_dfbdf90e'))
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('warning')
                    ->visible(fn ($record) => ! $isPackageCompleted && $record->distribution_status === DistributionStatus::Pending)
                    ->schema([
                        Select::make('new_beneficiary_id')
                            ->label(__('messages.ui_c077d9d5'))
                            ->options(function () use ($package) {
                                $tenantId = Filament::getTenant()?->id;
                                $alreadyAssigned = AssistanceDistribution::where('assistance_package_id', $package->id)
                                    ->pluck('beneficiary_id');

                                return Beneficiary::whereHas('organizations', function ($q) use ($tenantId) {
                                    $q->where('organization_id', $tenantId);
                                })
                                    ->whereNotIn('id', $alreadyAssigned)
                                    ->pluck('full_name', 'id');
                            })
                            ->searchable()
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('messages.ui_e601c07d'))
                            ->placeholder(__('messages.ui_a5292bfc'))
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        $oldName = $record->beneficiary?->full_name;
                        $newBeneficiary = Beneficiary::find($data['new_beneficiary_id']);

                        // استبدال الهوية وإعادة تصفير حالة التوزيع للبديل الجديد وتدوين السبب
                        $record->update([
                            'beneficiary_id' => $newBeneficiary->id,
                            'distribution_status' => DistributionStatus::Pending,
                            'delivered_at' => null,
                            'notes' => __('messages.ui_601738cb').$data['notes'],
                        ]);

                        Notification::make()
                            ->title(__('messages.ui_9c69c832'))
                            ->body(__('messages.ui_b4e8f1a2', [
                                'old' => $oldName,
                                'new' => $newBeneficiary->full_name,
                            ]))
                            ->success()
                            ->send();
                    }),

                // 3. شطب وإزالة الفرد (يُخفى في حال التسليم أو إغلاق الحزمة بالكامل)
                DeleteAction::make()
                    ->label(__('messages.ui_b866ad96'))
                    ->modalHeading(__('messages.ui_65a19862'))
                    ->visible(fn ($record) => ! $isPackageCompleted && $record->distribution_status === DistributionStatus::Pending)
                    ->after(function ($record) use ($package) {
                        // إذا كان قد تم التسليم (حالة استثنائية)، نقوم بتقليص الصرف والمخزن معاً
                        if ($record->distribution_status === DistributionStatus::Delivered) {
                            $package->decrement('distributed_quantity');
                        }
                        $package->decrement('total_quantity');

                        Notification::make()
                            ->title(__('messages.ui_c3a2c5ff'))
                            ->warning()
                            ->send();
                    }),
            ]);
    }
}
