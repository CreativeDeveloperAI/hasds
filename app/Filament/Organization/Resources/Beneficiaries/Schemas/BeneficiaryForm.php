<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Schemas;

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

        // 1. جلب تعاريف الحقول الديناميكية الخاصة بهذه المؤسسة لتركيبها في الـ Form
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
                // القسم الأول: البيانات السيادية (تمنع التكرار على مستوى المنصة)
                Section::make('البيانات الأساسية')
                    ->schema([
                        TextInput::make('national_id')
                            ->label('رقم الهوية الوطنية')
                            ->required()
                            ->length(9)
                            ->live(onBlur: true) // فحص الهوية بمجرد الخروج من الحقل
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (empty($state)) return;
                                // البحث المتقاطع خلف الكواليس لمنع التكرار
                                $existing = Beneficiary::where('national_id', $state)->first();
                                if ($existing) {
                                    $set('full_name', $existing->full_name);
                                    $set('date_of_birth', $existing->date_of_birth);
                                    $set('is_existing_beneficiary', true);

                                    Filament::notify('warning', 'هذا المستفيد مسجل مسبقاً بالنظام. تم جلب بياناته السيادية، ويمكنك الآن تعبئة تقييم مؤسستك فقط.');
                                } else {
                                    $set('is_existing_beneficiary', false);
                                }
                            }),

                        TextInput::make('full_name')
                            ->label('الاسم الكامل (رب الأسرة)')
                            ->required()
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true), // قفل الاسم إذا كان مسجلاً مسبقاً

                        DatePicker::make('date_of_birth')
                            ->label('تاريخ الميلاد')
                            ->disabled(fn (Get $get) => $get('is_existing_beneficiary') === true),

                        Hidden::make('is_existing_beneficiary')->default(false),
                    ])->columns(3),

                // القسم الثاني: التقييم الميداني والتواصل (خاص بالمؤسسة الحالية)
                Section::make('التقييم الميداني الحالي للمؤسسة')
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
                        TextInput::make('pivot_monthly_income')
                            ->label('الدخل الشهري الحقيقي (بالشيكل)')
                            ->numeric()
                            ->default(0),
                        Toggle::make('pivot_is_displaced')
                            ->label('هل العائلة نازحة حالياً؟')
                            ->live(),
                        TextInput::make('pivot_current_shelter_type')
                            ->label('نوع المأوى الحالي (خيمة / مركز إيواء)')
                            ->visible(fn (Get $get) => $get('pivot_is_displaced') === true),
                    ])->columns(3),

                // القسم الثالث: الحقول الديناميكية المحوكمة للمؤسسة
                Section::make('المؤشرات والمواصفات الخاصة بالجمعية')
                    ->description('حقول ديناميكية تم ضبطها مسبقاً لتسهيل الفلترة والتقارير')
                    ->schema($dynamicSchema)
                    ->columns(4)
                    ->visible(count($dynamicSchema) > 0),
            ]);
    }

}
