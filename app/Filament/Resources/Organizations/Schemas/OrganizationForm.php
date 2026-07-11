<?php

namespace App\Filament\Resources\Organizations\Schemas;

use App\Enums\OrganizationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationForm
{
    /**
     * إعداد حقول استمارة إضافة وتعديل المؤسسات الشريكة
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              Section::make('المعلومات الأساسية والترخيص')
                    ->description('البيانات الرسمية وهوية المؤسسة الشريكة')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المؤسسة الشريكة')
                            ->placeholder('مثال: جمعية غزة للإغاثة والتنمية')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('license_number')
                            ->label('رقم الترخيص الرسمي')
                            ->placeholder('مثال: 7023/ج')
                            ->maxLength(100),
                        TextInput::make('email')
                            ->label('البريد الإلكتروني الرسمي')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label('رقم هاتف التواصل')
                            ->tel()
                            ->required(),
                    ])->columns(2),

                Section::make('العمليات الميدانية والحوكمة')
                    ->description('الخيارات التشغيلية للتحكم بنزاهة التوزيع ومقرات العمل')
                    ->schema([
                        TextInput::make('hq_address')
                            ->label('المقر الميداني النشط حالياً في غزة')
                            ->placeholder('مثال: دير البلح - بجوار مستشفى شهداء الأقصى')
                            ->required(),
                        TextInput::make('primary_contact_person')
                            ->label('اسم المنسق الميداني المسؤول')
                            ->placeholder('الاسم الرباعي للمنسق')
                            ->required(),
                        Toggle::make('enable_cross_checking')
                            ->label('تفعيل التدقيق المتقاطع لمنع التكرار (Anti-Double-Dipping)')
                            ->helperText('عند تفعيل هذا الخيار، سيتم حظر صرف المساعدات المزدوجة لهذا الشريك مع بقية الشركاء تلقائياً.')
                            ->default(true),
                        Select::make('status')
                            ->label('حالة الحساب والمصادقة')
                            ->options(OrganizationStatus::class) // استدعاء مباشر لـ Enum حالة الحساب
                            ->required()
                            ->default(OrganizationStatus::Pending->value),
                    ])->columns(2),
            ]);
    }
}
