<?php

namespace App\Filament\Organization\Resources\AssistancePackages\Schemas;

use App\Enums\AssistancePackageStatus;
use App\Enums\AssistancePackageType;
use App\Enums\CurrentShelterType;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use App\Models\Beneficiary;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AssistancePackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        // الجزء الأيمن: استمارة حزمة المساعدة وشروط الاستهداف الموسعة
                        Grid::make(1)
                            ->schema([
                                Section::make('المعلومات الأساسية للمساعدة')
                                    ->description('تعريف طبيعة المساعدة والكميات المتاحة للصرف الميداني')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('اسم المنحة / حزمة المساعدة')
                                            ->placeholder('مثال: سلة الخضار الطازجة للأسر المتعففة')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('package_type')
                                            ->label('نوع المساعدة الموزعة')
                                            ->options(AssistancePackageType::class) // استخدام الـ Enum المربوط للنوع
                                            ->required()
                                            ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),

                                        TextInput::make('total_quantity')
                                            ->label('إجمالي عدد الحصص المتوفرة للتوزيع')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->placeholder('مثال: 500'),
                                        Textarea::make('description')
                                        ->label('محتويات الحزمة/وصف عن الحزمة')
                                        ->columnSpanFull()
                                    ])->columns(3),
                                Section::make('محرك التدقيق المتقاطع ومنع الصرف المكرر (Anti-Double-Dipping Engine)')
                                    ->description('التحقق التلقائي من عدم حصول العائلات على مساعدات متقاربة تحقيقاً للعدالة ومطابقة معايير الحظر اللحظي')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('target_prev_assistance_filter')
                                                    ->label('حالة فحص الاستفادة السابقة')
                                                    ->options([
                                                        'none' => 'لا تفعل (تجاوز الفحص)',
                                                        'received' => 'استهدف فقط من استلموا سابقاً',
                                                        'not_received' => 'استبعد من استلموا سابقاً (منع التكرار ⚠️)',
                                                    ])
                                                    ->default('none')
                                                    ->required()
                                                    ->live()
                                                    ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),

                                                Select::make('target_prev_assistance_type')
                                                    ->label('نوع المساعدة السابقة المراد التحقق منها')
                                                    ->options([
                                                        'any' => 'أي نوع مساعدة إغاثية',
                                                        'food' => 'مساعدات غذائية فقط',
                                                        'cash' => 'مساعدات نقدية فقط',
                                                        'medical' => 'مستلزمات طبية وأدوية',
                                                        'clothing' => 'كسوة وملابس شتوية',
                                                    ])
                                                    ->placeholder('اختر نوع الصرف للتحقق')
                                                    ->visible(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->required(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->live()
                                                    ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),

                                                TextInput::make('target_prev_assistance_days')
                                                    ->label('المدى الزمني للمطابقة والتحقق (بالأيام)')
                                                    ->numeric()
                                                    ->default(30)
                                                    ->minValue(1)
                                                    ->placeholder('مثال: 30 يوم')
                                                    ->visible(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->required(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->live()
                                                    ->helperText('مثال: 30 يوماً للتحقق من الصرف الحالي، أو يومين للحملات العاجلة')
                                                    ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),
                                            ]),
                                    ]),

                                Section::make('محددات استحقاق الاحتياج والنقاط')
                                    ->description('حصر الاستهداف بناءً على درجات الأولوية المحسوبة بنظام النقاط')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('target_min_score')
                                                    ->label('الحد الأدنى لنقاط الاستحقاق الميداني (Min Score)')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->live()
                                                    ->helperText('استهداف الأسر الأكثر احتياجاً فقط بدءاً من هذه الدرجة'),

                                                TextInput::make('target_max_score')
                                                    ->label('الحد الأقصى لنقاط الاستحقاق الميداني (Max Score)')
                                                    ->numeric()
                                                    ->default(100)
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->live(),
                                            ]),
                                    ]),

                                Section::make('محرك الاستهداف الديموغرافي والسيادي المطور (Targeting Engine)')
                                    ->description('تصفية وفلترة العائلات بناءً على معطياتها الشخصية والاجتماعية المسجلة بالسجل الوطني')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('target_gender')
                                                    ->label('جنس رب الأسرة')
                                                    ->options(Gender::class)
                                                    ->nullable()
                                                    ->live()
                                                    ->placeholder('الجميع (ذكور وإناث)'),

                                                Select::make('target_marital_status')
                                                    ->label('الحالة الاجتماعية المستهدفة')
                                                    ->options(MaritalStatus::class)
                                                    ->nullable()
                                                    ->live()
                                                    ->placeholder('الجميع (متزوج، أرمل، مطلق...)')
                                                    ->helperText('مثال: أرملة لاستهداف الأرامل فقط'),

                                                Select::make('target_vital_status')
                                                    ->label('الوضع الحيوي لرب الأسرة')
                                                    ->options(VitalStatus::class)
                                                    ->nullable()
                                                    ->live()
                                                    ->placeholder('الجميع (أحياء ومفقودين...)')
                                                    ->helperText('مثال: شهيد لاستهداف أسر الشهداء الفاقدة للمعيل'),
                                            ]),

                                        Section::make('معايير التركيبة الأسرية والاحتياجات الميدانية الخاصة')
                                            ->compact()
                                            ->schema([
                                                Grid::make(3)
                                                    ->schema([
                                                        Toggle::make('target_has_children_under_5')
                                                            ->label('يوجد أطفال دون سن الـ 5 سنوات')
                                                            ->live(),

                                                        Toggle::make('target_has_elderly')
                                                            ->label('يوجد كبار سن بالأسرة (فوق 60 سنة)')
                                                            ->live(),

                                                        Toggle::make('target_has_pregnant_or_lactating')
                                                            ->label('يوجد نساء حوامل أو مرضعات بالأسرة')
                                                            ->live(),
                                                    ]),
                                            ]),
                                    ]),

                                Section::make('محددات الوضع الصحي والطبي الميداني')
                                    ->description('فرز الأسر بناءً على وجود احتياج طبي أو إعاقة حركية مسجلة ميدانياً')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Toggle::make('target_has_disability')
                                                    ->label('وجود حالات إعاقة حركية/جسدية بالأسرة')
                                                    ->live(),

                                                Toggle::make('target_has_recent_injury')
                                                    ->label('وجود مصابي حرب وإصابات حديثة بالأسرة')
                                                    ->live(),

                                                Toggle::make('target_has_chronic_disease')
                                                    ->label('وجود حالات تعاني من أمراض مزمنة')
                                                    ->live(),
                                            ]),
                                    ]),

                                Section::make('محددات السكن ومخيمات النزوح الحالية')
                                    ->description('حصر الاستهداف بناءً على الموقع الجغرافي للمأوى والخيام')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Toggle::make('target_is_displaced')
                                                    ->label('استهداف النازحين فقط في الميدان')
                                                    ->live(),

                                                TextInput::make('target_displacement_location')
                                                    ->label('منطقة أو مخيم النزوح الحالي')
                                                    ->placeholder('مثال: دير البلح')
                                                    ->visible(fn (Get $get) => $get('target_is_displaced') === true)
                                                    ->live(),

                                                Select::make('target_shelter_type')
                                                    ->label('نوع مأوى النزوح المستهدف')
                                                    ->options(CurrentShelterType::class)
                                                    ->visible(fn (Get $get) => $get('target_is_displaced') === true)
                                                    ->live()
                                                    ->placeholder('كافة أنواع المآوي والخيام'),
                                            ]),
                                    ]),
                            ])->columnSpan(2),

                        // الجزء الأيسر: محرك التقدير اللحظي والجدولة والنشاط
                        Grid::make(1)
                            ->schema([
                                Section::make('مؤشر التقدير والتحليل اللحظي')
                                    ->description('حساب فوري للأسر المطابقة بناءً على الفلاتر المدخلة ونطاق عمل جمعيتك')
                                    ->schema([
                                        Placeholder::make('matching_count')
                                            ->label('عدد الأسر المستحقة حالياً')
                                            ->content(function (Get $get) {
                                                $tenantId = Filament::getTenant()?->id;

                                                // بناء استعلام الفرز المتقاطع المتطور للمطابقة الحية
                                                $query = Beneficiary::query()
                                                    ->whereHas('organizations', function ($q) use ($tenantId, $get) {
                                                        $q->where('organization_id', $tenantId);

                                                        // 1. فلترة نطاق السكور الإغاثي
                                                        $minScore = intval($get('target_min_score') ?? 0);
                                                        $maxScore = intval($get('target_max_score') ?? 100);
                                                        $q->whereBetween('priority_score', [$minScore, $maxScore]);

                                                        // 2. فلترة شروط السكن والنزوح والمكان
                                                        if (filter_var($get('target_is_displaced'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('is_displaced', true);

                                                            if ($loc = $get('target_displacement_location')) {
                                                                $q->where('current_displacement_location', 'like', "%{$loc}%");
                                                            }

                                                            if ($shelter = $get('target_shelter_type')) {
                                                                $q->where('current_shelter_type', $shelter);
                                                            }
                                                        }

                                                        // 3. فلترة الوضع الصحي والطبي والاصابات
                                                        if (filter_var($get('target_has_disability'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('has_disability', true);
                                                        }
                                                        if (filter_var($get('target_has_recent_injury'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('has_recent_injury', true);
                                                        }
                                                        if (filter_var($get('target_has_chronic_disease'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('has_chronic_disease', true);
                                                        }

                                                        // 4. فلترة التركيبة الأسرية الديموغرافية المتغيرة
                                                        if (filter_var($get('target_has_children_under_5'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('children_under_5_count', '>', 0);
                                                        }
                                                        if (filter_var($get('target_has_elderly'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('elderly_count', '>', 0);
                                                        }
                                                        if (filter_var($get('target_has_pregnant_or_lactating'), FILTER_VALIDATE_BOOLEAN)) {
                                                            $q->where('pregnant_or_lactating_count', '>', 0);
                                                        }
                                                    });

                                                // 5. فلترة المعايير السيادية الشخصية لرب الأسرة
                                                if ($gender = $get('target_gender')) {
                                                    $query->where('gender', $gender);
                                                }
                                                if ($marital = $get('target_marital_status')) {
                                                    $query->where('marital_status', $marital);
                                                }
                                                if ($vital = $get('target_vital_status')) {
                                                    $query->where('vital_status', $vital);
                                                }

                                                $count = $query->count();

                                                return view('filament.components.beneficiary-counter', [
                                                    'count' => $count,
                                                    'quantity' => intval($get('total_quantity') ?? 0),
                                                ]);
                                            }),
                                    ]),

                                Section::make('الجدولة الزمنية والنشاط')
                                    ->schema([
                                        DatePicker::make('start_date')
                                            ->label('تاريخ بدء التوزيع')
                                            ->default(now())
                                            ->required(),

                                        DatePicker::make('end_date')
                                            ->label('تاريخ انتهاء التوزيع الميداني'),


                                        Select::make('status')
                                            ->label('حالة الحزمة')
                                            ->options(AssistancePackageStatus::class) // استخدام الـ Enum المربوط للحالة
                                            ->required()
                                            ->default(AssistancePackageStatus::Active->value),
                                    ]),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
