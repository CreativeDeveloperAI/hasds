<?php

namespace App\Filament\Resources\ScoringPolicies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScoringPolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('معطيات المعيار وأولوية الاحتياج')
                    ->description('تحديد الوزن النسبي للمتغير الاجتماعي أو الصحي ميدانياً')
                    ->schema([
                        TextInput::make('policy_name')
                            ->label('اسم المعيار بالعربية')
                            ->required()
                            ->placeholder('مثال: نقاط الإقامة في خيمة هشة'),
                        Select::make('policy_key')
                            ->label('المؤشر الميداني المربوط (قاعدة البيانات)')
                            ->required()
                            ->options([
                                // مؤشرات النزوح والمأوى
                                'is_displaced' => 'المواطن نازح في الميدان (is_displaced)',
                                'shelter_tent' => 'نوع المأوى الحالي: خيمة (current_shelter_type = tent)',
                                'shelter_center' => 'نوع المأوى الحالي: مركز إيواء (current_shelter_type = shelter_center)',

                                // مؤشرات صحية وطبية (Pivot)
                                'has_disability' => 'المواطن من ذوي الاحتياجات الخاصة (has_disability)',
                                'has_chronic_disease' => 'يعاني من أمراض مزمنة (has_chronic_disease)',
                                'has_recent_injury' => 'لديه إصابة حرب حديثة (has_recent_injury)',

                                // مؤشرات سيادية (Sovereign Beneficiary)
                                'vital_status_martyred' => 'حالة المواطن السيادية: شهيد (vital_status = martyred)',
                                'vital_status_missing' => 'حالة المواطن السيادية: مفقود (vital_status = missing)',
                                'gender_female' => 'المواطن أنثى / احتمالية معيل أسرة (gender = female)',

                                // مؤشرات مركبة ديموغرافية واقتصادية
                                'family_large' => 'عائلة كبيرة العدد (أكثر من 5 أفراد)',
                                'no_income' => 'بلا دخل مادي شهري نهائياً (monthly_income = 0)',
                            ])
                            ->disabled(fn ($record) => $record !== null) // حظر التغيير بعد الإنشاء للحفاظ على استقرار الكود
                            ->helperText('اختر الحقل التقني المراد ربط النقاط به ليقوم محرك الاحتساب بمطابقته تلقائياً ومنع الأخطاء الإملائية.'),

                        Select::make('category')
                            ->label('تصنيف المؤشر')
                            ->options([
                                'social' => 'مؤشرات اجتماعية وديموغرافية',
                                'health' => 'مؤشرات طبية وصحية',
                                'shelter' => 'مؤشرات النزوح والمأوى',
                                'financial' => 'مؤشرات مادية واقتصادية',
                            ])
                            ->required(),
                        TextInput::make('points_weight')
                            ->label('النقاط الممنوحة (من 100)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->placeholder('مثال: 25'),
                        Toggle::make('is_active')
                            ->label('تفعيل هذا المعيار في الاحتساب الفوري')
                            ->default(true),
                    ])->columns(4),
            ]);
    }
}
