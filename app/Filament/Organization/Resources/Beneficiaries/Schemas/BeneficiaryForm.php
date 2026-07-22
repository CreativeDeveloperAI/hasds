<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Schemas;

use App\Enums\CurrentShelterType;
use App\Enums\DisabilityType;
use App\Enums\Gender;
use App\Enums\InjurySeverity;
use App\Enums\MaritalStatus;
use App\Enums\ShelterCondition;
use App\Enums\VitalStatus;
use App\Models\Beneficiary;
use App\Models\CustomFieldDefinition;
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
                Section::make(__('messages.ui_0fa59db1'))
                    ->description(__('messages.ui_be932dae'))
                    ->schema([
                        TextInput::make('national_id')
                            ->label(__('messages.ui_3ca30c31'))
                            ->required()
                            ->length(9)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (empty($state)) {
                                    return;
                                }
                                $existing = Beneficiary::where('national_id', $state)->first();
                                if ($existing) {
                                    $set('full_name', $existing->full_name);
                                    $set('date_of_birth', $existing->date_of_birth);
                                    $set('gender', $existing->gender->value ?? $existing->gender);
                                    $set('marital_status', $existing->marital_status->value ?? $existing->marital_status);
                                    $set('vital_status', $existing->vital_status->value ?? $existing->vital_status);
                                    $set('personal_phone', $existing->personal_phone);
                                    $set('is_existing_beneficiary', true);

                                    Filament::notify('warning', __('messages.ui_9e094a3d'));
                                } else {
                                    $set('is_existing_beneficiary', false);
                                }
                            }),

                        TextInput::make('full_name')
                            ->label(__('messages.ui_03fe938f'))
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        DatePicker::make('date_of_birth')
                            ->label(__('messages.ui_2d49375e'))
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Select::make('gender')
                            ->label(__('messages.ui_3d838022'))
                            ->options(Gender::class) // استخدام الـ Enum الجديد للجنس
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Select::make('marital_status')
                            ->label(__('messages.ui_8c3f7498'))
                            ->options(MaritalStatus::class) // استخدام الـ Enum الجديد للحالة الاجتماعية
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Select::make('vital_status')
                            ->label(__('messages.ui_c5edd78f'))
                            ->options(VitalStatus::class) // استخدام الـ Enum الجديد للوضع الحيوي
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        TextInput::make('personal_phone')
                            ->label(__('messages.ui_f31ea5d9'))
                            ->tel()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Hidden::make('is_existing_beneficiary')->dehydrated(false)->default(false),
                    ])->columns(3),

                // القسم الثاني: التقييم الميداني والديموغرافي التفصيلي الخاص بالمؤسسة
                Section::make(__('messages.ui_ac34961b'))
                    ->description(__('messages.ui_1c9b769c'))
                    ->schema([
                        TextInput::make('pivot_phone_number')
                            ->label(__('messages.ui_05a491b4'))
                            ->tel()
                            ->required(),

                        TextInput::make('pivot_family_members_count')
                            ->label(__('messages.ui_e390db79'))
                            ->numeric()
                            ->default(1)
                            ->required(),

                        TextInput::make('pivot_children_under_5_count')
                            ->label(__('messages.ui_3a435866'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('pivot_elderly_count')
                            ->label(__('messages.ui_379dc51a'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('pivot_pregnant_or_lactating_count')
                            ->label(__('messages.ui_4cacc2dc'))
                            ->numeric()
                            ->default(0)// ضروري جداً
                            ->required(),

                        TextInput::make('pivot_monthly_income')
                            ->label(__('messages.ui_b0e7ecee'))
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('pivot_income_source')
                            ->label(__('messages.ui_e6389239'))
                            ->placeholder(__('messages.ui_df0f3e4d')),

                        Toggle::make('pivot_has_alternative_assistance')
                            ->label(__('messages.ui_b2247778'))
                            ->default(false),
                    ])->columns(4),

                // القسم الثالث: المؤشرات الصحية والطبية الميدانية (تم نقلها هنا)
                Section::make(__('messages.ui_553b4902'))
                    ->schema([
                        Toggle::make('pivot_has_disability')
                            ->label(__('messages.ui_ac3c2fb1'))
                            ->live(),

                        Select::make('pivot_disability_type')
                            ->label(__('messages.ui_8bcbf345'))
                            ->options(DisabilityType::class) // استخدام الـ Enum الجديد لنوع الإعاقة
                            ->visible(fn (Get $get) => $get('pivot_has_disability') === true)
                            ->required(fn (Get $get) => $get('pivot_has_disability') === true),

                        Toggle::make('pivot_has_chronic_disease')
                            ->label(__('messages.ui_dcc53354'))
                            ->default(false),

                        Toggle::make('pivot_has_recent_injury')
                            ->label(__('messages.ui_b306a51f'))
                            ->live(),

                        Select::make('pivot_injury_severity')
                            ->label(__('messages.ui_af7c53d9'))
                            ->options(InjurySeverity::class) // استخدام الـ Enum الجديد لشدة الإصابة
                            ->visible(fn (Get $get) => $get('pivot_has_recent_injury') === true)
                            ->required(fn (Get $get) => $get('pivot_has_recent_injury') === true),
                    ])->columns(3),

                // القسم الرابع: تفاصيل النزوح والمأوى
                Section::make(__('messages.ui_06aba1a8'))
                    ->schema([
                        Toggle::make('pivot_is_displaced')
                            ->label(__('messages.ui_29dc7b73'))
                            ->live(),

                        TextInput::make('pivot_current_displacement_location')
                            ->label(__('messages.ui_293040c4'))
                            ->placeholder(__('messages.ui_ec089448'))
                            ->visible(fn (Get $get) => $get('pivot_is_displaced') === true)
                            ->required(fn (Get $get) => $get('pivot_is_displaced') === true),

                        Select::make('pivot_current_shelter_type')
                            ->label(__('messages.ui_1bf6729d'))
                            ->options(CurrentShelterType::class) // استخدام الـ Enum الجديد والمطوّر بدلاً من الـ TextInput السابق
                            ->visible(fn (Get $get) => $get('pivot_is_displaced') === true)
                            ->required(fn (Get $get) => $get('pivot_is_displaced') === true),

                        Select::make('pivot_shelter_condition')
                            ->label(__('messages.ui_23bfe7bc'))
                            ->options(ShelterCondition::class) // استخدام الـ Enum الجديد لجودة السكن
                            ->required(),
                    ])->columns(4),

                // القسم الخامس: الحقول الديناميكية الخاصة بالجمعية
                Section::make(__('messages.ui_f907a809'))
                    ->description(__('messages.ui_dc35c84c'))
                    ->schema($dynamicSchema)
                    ->columns(4)
                    ->visible(count($dynamicSchema) > 0),
            ]);
    }
}
