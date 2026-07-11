<?php

namespace App\Filament\Organization\Resources\AssistancePackages\RelationManagers;

use App\Models\AssistanceDistribution;
use App\Models\Beneficiary;
use App\Enums\DistributionStatus;
use App\Enums\AssistancePackageStatus;
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

    protected static ?string $title = 'كشف استحقاق وتوزيع المساعدات للأسرة';
    protected static ?string $modelLabel = 'مستحق';
    protected static ?string $pluralModelLabel = 'سجل التوزيع والمستحقين';

    public function table(Table $table): Table
    {
        $package = $this->getOwnerRecord();
        $isPackageCompleted = $package->status === AssistancePackageStatus::Completed;

        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('beneficiary.full_name')
                    ->label('الاسم الكامل للمواطن')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('beneficiary.national_id')
                    ->label('رقم الهوية الوطنية')
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('distribution_status')
                    ->label('حالة التسليم')
                    ->badge(), // يقرأ التسميات والألوان تلقائياً من الـ Enum المربوطة

                TextColumn::make('delivered_at')
                    ->label('تاريخ ووقت الاستلام')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('لم يتم الصرف بعد')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('ملاحظات الصرف والاستبدال الميداني')
                    ->placeholder('لا يوجد ملاحظات')
                    ->limit(30),
            ])
            ->filters([
                SelectFilter::make('distribution_status')
                    ->label('تصفية حسب حالة التسليم')
                    ->options(DistributionStatus::class),
            ])
            ->headerActions([
                // إخفاء زر الإضافة في حالتين:
                // 1. إذا اكتملت الحصص المرصودة (المستفيدين الفعليين >= total_quantity)
                // 2. إذا تم إنهاء وإغلاق الدورة الإغاثية بالكامل
                CreateAction::make()
                    ->label('إضافة مستحق يدوي للحزمة')
                    ->modalHeading('تسجيل وإضافة مستفيد خارج كشوفات الفرز')
                    ->visible(function () use ($package, $isPackageCompleted) {
                        if ($isPackageCompleted) return false;

                        $assignedCount = AssistanceDistribution::where('assistance_package_id', $package->id)->count();
                        return $assignedCount < $package->total_quantity;
                    })
                    ->schema([
                        Select::make('beneficiary_id')
                            ->label('اختر المواطن من السجل الوطني لجمعيتك')
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
                            ->label('سبب الإضافة اليدوية خارج محرك الاستهداف')
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
                    ->label('تسليم المساعدة')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$isPackageCompleted && $record->distribution_status === DistributionStatus::Pending)
                    ->action(function ($record) use ($package) {
                        $record->update([
                            'distribution_status' => DistributionStatus::Delivered,
                            'delivered_at' => now(),
                        ]);

                        // تحديث تلقائي لكمية التوزيع الفعلي بداخل الحزمة الأساسية
                        $package->increment('distributed_quantity');

                        Notification::make()
                            ->title('تم توثيق عملية الصرف الميداني بنجاح')
                            ->success()
                            ->send();
                    }),

                // 2. إجراء الاستبدال الذكي (يُخفى في حال التسليم الفعلي أو إغلاق الحزمة بالكامل)
                Action::make('replace_beneficiary')
                    ->label('استبدال بمستفيد بديل')
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('warning')
                    ->visible(fn ($record) => !$isPackageCompleted && $record->distribution_status === DistributionStatus::Pending)
                    ->schema([
                        Select::make('new_beneficiary_id')
                            ->label('اختر المواطن البديل من سجلات جمعيتك')
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
                            ->label('سبب الاستبدال الميداني الموثق')
                            ->placeholder('مثال: عدم تواجده بالمنطقة الحالية / غادر مخيم النزوح')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) use ($package) {
                        $oldName = $record->beneficiary?->full_name;
                        $newBeneficiary = Beneficiary::find($data['new_beneficiary_id']);

                        // استبدال الهوية وإعادة تصفير حالة التوزيع للبديل الجديد وتدوين السبب
                        $record->update([
                            'beneficiary_id' => $newBeneficiary->id,
                            'distribution_status' => DistributionStatus::Pending,
                            'delivered_at' => null,
                            'notes' => 'تم الاستبدال بالمستفيد البديل بقرار الباحث الميداني. السبب: ' . $data['notes'],
                        ]);

                        Notification::make()
                            ->title('تم الاستبدال بنجاح')
                            ->body("تم إيقاف المواطن {$oldName} واستبداله فوراً بالمواطن {$newBeneficiary->full_name}")
                            ->success()
                            ->send();
                    }),

                // 3. شطب وإزالة الفرد (يُخفى في حال التسليم أو إغلاق الحزمة بالكامل)
                DeleteAction::make()
                    ->label('إزالة الفرد')
                    ->modalHeading('شطب المواطن من كشف توزيع هذه الحزمة')
                    ->visible(fn ($record) => !$isPackageCompleted && $record->distribution_status === DistributionStatus::Pending)
                    ->after(function ($record) use ($package) {
                        // إذا كان قد تم التسليم (حالة استثنائية)، نقوم بتقليص الصرف والمخزن معاً
                        if ($record->distribution_status === DistributionStatus::Delivered) {
                            $package->decrement('distributed_quantity');
                        }
                        $package->decrement('total_quantity');

                        Notification::make()
                            ->title('تم شطب الفرد بنجاح وتقليص الحصص المتوفرة')
                            ->warning()
                            ->send();
                    }),
            ]);
    }
}
