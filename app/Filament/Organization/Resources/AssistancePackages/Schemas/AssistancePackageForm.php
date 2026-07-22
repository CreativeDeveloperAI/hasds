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
                                Section::make(__('messages.ui_adb52785'))
                                    ->description(__('messages.ui_69e610b9'))
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('messages.ui_4965458d'))
                                            ->placeholder(__('messages.ui_d560100c'))
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('package_type')
                                            ->label(__('messages.ui_36a1f8be'))
                                            ->options(AssistancePackageType::class) // استخدام الـ Enum المربوط للنوع
                                            ->required()
                                            ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),

                                        TextInput::make('total_quantity')
                                            ->label(__('messages.ui_e04b1310'))
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->placeholder(__('messages.ui_e7b058e5')),
                                        Textarea::make('description')
                                            ->label(__('messages.ui_47c94644'))
                                            ->columnSpanFull(),
                                    ])->columns(3),
                                Section::make(__('messages.ui_bc9bc28c'))
                                    ->description(__('messages.ui_34881d66'))
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('target_prev_assistance_filter')
                                                    ->label(__('messages.ui_f76af8f3'))
                                                    ->options([
                                                        'none' => __('messages.ui_1bc3120e'),
                                                        'received' => __('messages.ui_f725238b'),
                                                        'not_received' => __('messages.ui_b8f76f1c'),
                                                    ])
                                                    ->default('none')
                                                    ->required()
                                                    ->live()
                                                    ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),

                                                Select::make('target_prev_assistance_type')
                                                    ->label(__('messages.ui_96cfc9d2'))
                                                    ->options([
                                                        'any' => __('messages.ui_d3a52aff'),
                                                        'food' => __('messages.ui_82ca845c'),
                                                        'cash' => __('messages.ui_fcf36bc0'),
                                                        'medical' => __('messages.ui_513fd73e'),
                                                        'clothing' => __('messages.ui_a9cdbeb3'),
                                                    ])
                                                    ->placeholder(__('messages.ui_4366e3f2'))
                                                    ->visible(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->required(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->live()
                                                    ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),

                                                TextInput::make('target_prev_assistance_days')
                                                    ->label(__('messages.ui_869d7ace'))
                                                    ->numeric()
                                                    ->default(30)
                                                    ->minValue(1)
                                                    ->placeholder(__('messages.ui_62fa30dd'))
                                                    ->visible(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->required(fn (Get $get) => $get('target_prev_assistance_filter') !== 'none')
                                                    ->live()
                                                    ->helperText(__('messages.ui_a949e46c'))
                                                    ->disabled(fn ($record) => $record?->status === AssistancePackageStatus::Completed),
                                            ]),
                                    ]),

                                Section::make(__('messages.ui_7d663541'))
                                    ->description(__('messages.ui_0c043b42'))
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('target_min_score')
                                                    ->label(__('messages.ui_5a9c7d10'))
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->live()
                                                    ->helperText(__('messages.ui_6ca0ea75')),

                                                TextInput::make('target_max_score')
                                                    ->label(__('messages.ui_91508033'))
                                                    ->numeric()
                                                    ->default(100)
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->live(),
                                            ]),
                                    ]),

                                Section::make(__('messages.ui_159ea5f0'))
                                    ->description(__('messages.ui_64e39294'))
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('target_gender')
                                                    ->label(__('messages.ui_e0947ce4'))
                                                    ->options(Gender::class)
                                                    ->nullable()
                                                    ->live()
                                                    ->placeholder(__('messages.ui_b673e5c8')),

                                                Select::make('target_marital_status')
                                                    ->label(__('messages.ui_b2bb1eb5'))
                                                    ->options(MaritalStatus::class)
                                                    ->nullable()
                                                    ->live()
                                                    ->placeholder(__('messages.ui_06290c4b'))
                                                    ->helperText(__('messages.ui_8c1ad9b2')),

                                                Select::make('target_vital_status')
                                                    ->label(__('messages.ui_02bdfcea'))
                                                    ->options(VitalStatus::class)
                                                    ->nullable()
                                                    ->live()
                                                    ->placeholder(__('messages.ui_e4a4063d'))
                                                    ->helperText(__('messages.ui_43e29986')),
                                            ]),

                                        Section::make(__('messages.ui_34d60017'))
                                            ->compact()
                                            ->schema([
                                                Grid::make(3)
                                                    ->schema([
                                                        Toggle::make('target_has_children_under_5')
                                                            ->label(__('messages.ui_bd3da1f3'))
                                                            ->live(),

                                                        Toggle::make('target_has_elderly')
                                                            ->label(__('messages.ui_bf6e9262'))
                                                            ->live(),

                                                        Toggle::make('target_has_pregnant_or_lactating')
                                                            ->label(__('messages.ui_980db63e'))
                                                            ->live(),
                                                    ]),
                                            ]),
                                    ]),

                                Section::make(__('messages.ui_ce4bc60d'))
                                    ->description(__('messages.ui_9defaf01'))
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Toggle::make('target_has_disability')
                                                    ->label(__('messages.ui_bb4add0d'))
                                                    ->live(),

                                                Toggle::make('target_has_recent_injury')
                                                    ->label(__('messages.ui_87e2847f'))
                                                    ->live(),

                                                Toggle::make('target_has_chronic_disease')
                                                    ->label(__('messages.ui_a27a3e47'))
                                                    ->live(),
                                            ]),
                                    ]),

                                Section::make(__('messages.ui_c7e76487'))
                                    ->description(__('messages.ui_3187eb08'))
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Toggle::make('target_is_displaced')
                                                    ->label(__('messages.ui_f908e209'))
                                                    ->live(),

                                                TextInput::make('target_displacement_location')
                                                    ->label(__('messages.ui_60cf56ec'))
                                                    ->placeholder(__('messages.ui_5854c7e7'))
                                                    ->visible(fn (Get $get) => $get('target_is_displaced') === true)
                                                    ->live(),

                                                Select::make('target_shelter_type')
                                                    ->label(__('messages.ui_01cfa937'))
                                                    ->options(CurrentShelterType::class)
                                                    ->visible(fn (Get $get) => $get('target_is_displaced') === true)
                                                    ->live()
                                                    ->placeholder(__('messages.ui_2c80f7d9')),
                                            ]),
                                    ]),
                            ])->columnSpan(2),

                        // الجزء الأيسر: محرك التقدير اللحظي والجدولة والنشاط
                        Grid::make(1)
                            ->schema([
                                Section::make(__('messages.ui_632613cd'))
                                    ->description(__('messages.ui_af1044b9'))
                                    ->schema([
                                        Placeholder::make('matching_count')
                                            ->label(__('messages.ui_c463375b'))
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

                                Section::make(__('messages.ui_aac26e05'))
                                    ->schema([
                                        DatePicker::make('start_date')
                                            ->label(__('messages.ui_a9aceb5d'))
                                            ->default(now())
                                            ->required(),

                                        DatePicker::make('end_date')
                                            ->label(__('messages.ui_4b9d6ebd')),

                                        Select::make('status')
                                            ->label(__('messages.ui_1bcb4617'))
                                            ->options(AssistancePackageStatus::class) // استخدام الـ Enum المربوط للحالة
                                            ->required()
                                            ->default(AssistancePackageStatus::Active->value),
                                    ]),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
