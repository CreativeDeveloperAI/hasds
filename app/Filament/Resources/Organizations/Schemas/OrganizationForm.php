<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('المعلومات الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المؤسسة')
                            ->required(),
                        TextInput::make('license_number')
                            ->label('رقم الترخيص'),
                        TextInput::make('email')
                            ->label('البريد الإلكتروني الرسمي')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('رقم التواصل')
                            ->tel()
                            ->required(),
                    ])->columns(2),

                Section::make('التفاصيل الإدارية والعملياتية')
                    ->schema([
                        TextInput::make('hq_address')
                            ->label('المقر الحالي في غزة')
                            ->required(),
                        TextInput::make('primary_contact_person')
                            ->label('الشخص المسؤول / المنسق')
                            ->required(),
                        Toggle::make('enable_cross_checking')
                            ->label('تفعيل التدقيق المتقاطع لمنع التكرار')
                            ->default(true),
                        Select::make('status')
                            ->label('حالة الحساب في النظام')
                            ->options([
                                'pending' => 'قيد الانتظار (جديد)',
                                'approved' => 'مفعّل ونشط',
                                'suspended' => 'موقوف مؤقتاً',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columns(2),
            ]);
    }
}
