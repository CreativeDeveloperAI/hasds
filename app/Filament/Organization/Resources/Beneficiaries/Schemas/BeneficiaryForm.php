<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Schemas;

use App\Models\Beneficiary;
use App\Models\CustomFieldDefinition;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\VitalStatus;
use App\Enums\CurrentShelterType;
use App\Enums\ShelterCondition;
use App\Enums\DisabilityType;
use App\Enums\InjurySeverity;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class BeneficiaryForm
{
    public static function configure(Schema $schema): Schema
    {
        $tenantId = Filament::getTenant()?->id;

        // 1. جلب تعاريف الحقول الديناميكية المخصصة للمؤسسة
        $dynamicFields = CustomFieldDefinition::where('organization_id', $tenantId)->get();
        $dynamicSchema = [];

        foreach ($dynamicFields as $field) {
            $fieldComponent = match ($field->field_type) {
                'number' => TextInput::make("pivot_custom_fields.{$field->field_key}")->numeric(),
                'boolean' => Toggle::make("pivot_custom_fields.{$field->field_key}")->inline(false),
                'select' => Select::make("pivot_custom_fields.{$field->field_key}")->options($field->options ?? []),
                default => TextInput::make("pivot_custom_fields.{$field->field_key}"),
            };

            $fieldComponent->label($field->field_label)->required($field->is_required);
            $dynamicSchema[] = $fieldComponent;
        }

        return $schema
            ->columns(1)
            ->components([
                // القسم الأول: البيانات السيادية الوطنية (منع التكرار)
                Section::make('البيانات الأساسية السيادية')
                    ->description('البيانات الشخصية الثابتة للمواطن في السجل الوطني الموحد')
                    ->schema([
                        TextInput::make('national_id')
                            ->label('رقم الهوية الوطنية')
                            ->required()
                            ->length(9)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (empty($state)) return;
                                $existing = Beneficiary::where('national_id', $state)->first();
                                if ($existing) {
                                    $set('full_name', $existing->full_name);
                                    $set('date_of_birth', $existing->date_of_birth);
                                    $set('gender', $existing->gender->value ?? $existing->gender);
                                    $set('marital_status', $existing->marital_status->value ?? $existing->marital_status);
                                    $set('vital_status', $existing->vital_status->value ?? $existing->vital_status);
                                    $set('personal_phone', $existing->personal_phone);
                                    $set('is_existing_beneficiary', true);

                                    Filament::notify('warning', 'المستفيد مسجل مسبقاً بالنظام الموحد. تم جلب بياناته السيادية تلقائياً.');
                                } else {
                                    $set('is_existing_beneficiary', false);
                                }
                            }),

                        TextInput::make('full_name')
                            ->label('الاسم الكامل (رب الأسرة)')
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        DatePicker::make('date_of_birth')
                            ->label('تاريخ الميلاد')
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Select::make('gender')
                            ->label('الجنس')
                            ->options(Gender::class) // استخدام الـ Enum الجديد للجنس
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Select::make('marital_status')
                            ->label('الحالة الاجتماعية السيادية')
                            ->options(MaritalStatus::class) // استخدام الـ Enum الجديد للحالة الاجتماعية
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Select::make('vital_status')
                            ->label('الحالة الحيوية')
                            ->options(VitalStatus::class) // استخدام الـ Enum الجديد للوضع الحيوي
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        TextInput::make('personal_phone')
                            ->label('رقم جوال المواطن الشخصي الثابت')
                            ->tel()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Hidden::make('is_existing_beneficiary')->dehydrated(false)->default(false),
                    ])->columns(3),

                // القسم الثاني: التقييم الميداني والديموغرافي التفصيلي الخاص بالمؤسسة
                Section::make('التقييم الميداني الحالي للمؤسسة')
                    ->description('المعطيات الإنسانية والمادية المتغيرة بناءً على رصد الباحث الميداني للجمعية')
                    ->schema([
                        TextInput::make('pivot_phone_number')
                            ->label('رقم جوال التواصل الحالي بالميدان')
                            ->tel()
                            ->required(),

                        TextInput::make('pivot_family_members_count')
                            ->label('عدد أفراد الأسرة الحالية')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        TextInput::make('pivot_children_under_5_count')
                            ->label('عدد الأطفال دون 5 سنوات')
                            ->numeric()
                            ->default(0)
                        ->required(),

                        TextInput::make('pivot_elderly_count')
                            ->label('عدد كبار السن (فوق 60 سنة)')
                            ->numeric()
                            ->default(0)
                        ->required(),

                        TextInput::make('pivot_pregnant_or_lactating_count')
                            ->label('عدد النساء الحوامل أو المرضعات')
                            ->numeric()
                            ->default(0)// ضروري جداً
                            ->required(),

                        TextInput::make('pivot_monthly_income')
                            ->label('الدخل الشهري الحقيقي (بالشيكل)')
                            ->numeric()
                            ->default(0)
                        ->required(),

                        TextInput::make('pivot_income_source')
                            ->label('مصدر الدخل الحالي')
                            ->placeholder('مثال: عمالة يومية، مخصصات شؤون، بلا دخل'),

                        Toggle::make('pivot_has_alternative_assistance')
                            ->label('هل يتلقى مساعدات دورية من جهة أخرى؟')
                            ->default(false),
                    ])->columns(4),

                // القسم الثالث: المؤشرات الصحية والطبية الميدانية (تم نقلها هنا)
                Section::make('الوضع الصحي والطبي الميداني للأسرة')
                    ->schema([
                        Toggle::make('pivot_has_disability')
                            ->label('هل يوجد أفراد من ذوي الاحتياجات الخاصة؟')
                            ->live(),

                        Select::make('pivot_disability_type')
                            ->label('نوع الإعاقة الرئيسية بالأسرة')
                            ->options(DisabilityType::class) // استخدام الـ Enum الجديد لنوع الإعاقة
                            ->visible(fn (Get $get) => $get('pivot_has_disability') === true)
                            ->required(fn (Get $get) => $get('pivot_has_disability') === true),

                        Toggle::make('pivot_has_chronic_disease')
                            ->label('هل يعاني أحد الأفراد من أمراض مزمنة؟')
                            ->default(false),

                        Toggle::make('pivot_has_recent_injury')
                            ->label('هل يوجد مصاب حرب حديث بالأسرة؟')
                            ->live(),

                        Select::make('pivot_injury_severity')
                            ->label('خطورة الإصابة الحالية')
                            ->options(InjurySeverity::class) // استخدام الـ Enum الجديد لشدة الإصابة
                            ->visible(fn (Get $get) => $get('pivot_has_recent_injury') === true)
                            ->required(fn (Get $get) => $get('pivot_has_recent_injury') === true),
                    ])->columns(3),

                // القسم الرابع: تفاصيل النزوح والمأوى
                Section::make('معطيات السكن والنزوح الحالي')
                    ->schema([
                        Toggle::make('pivot_is_displaced')
                            ->label('هل العائلة نازحة حالياً؟')
                            ->live(),

                        TextInput::make('pivot_current_displacement_location')
                            ->label('المنطقة أو مخيم النزوح الحالي')
                            ->placeholder('مثال: دير البلح - معسكر الجنوب')
                            ->visible(fn (Get $get) => $get('pivot_is_displaced') === true)
                            ->required(fn (Get $get) => $get('pivot_is_displaced') === true),

                        Select::make('pivot_current_shelter_type')
                            ->label('نوع المأوى الحالي')
                            ->options(CurrentShelterType::class) // استخدام الـ Enum الجديد والمطوّر بدلاً من الـ TextInput السابق
                            ->visible(fn (Get $get) => $get('pivot_is_displaced') === true)
                            ->required(fn (Get $get) => $get('pivot_is_displaced') === true),

                        Select::make('pivot_shelter_condition')
                            ->label('حالة المأوى الحالي')
                            ->options(ShelterCondition::class) // استخدام الـ Enum الجديد لجودة السكن
                            ->required(),
                    ])->columns(4),

                // القسم الخامس: الحقول الديناميكية الخاصة بالجمعية
                Section::make('المؤشرات والمواصفات الخاصة بالجمعية')
                    ->description('حقول ديناميكية تم ضبطها مسبقاً لتسهيل الفلترة والتقارير')
                    ->schema($dynamicSchema)
                    ->columns(4)
                    ->visible(count($dynamicSchema) > 0),
            ]);
    }
}
