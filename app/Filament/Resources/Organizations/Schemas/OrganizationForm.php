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
                Section::make(__('messages.ui_91ef9f2c'))
                    ->description(__('messages.ui_d0efbed5'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('messages.ui_5bb4f592'))
                            ->placeholder(__('messages.ui_ae05b888'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('license_number')
                            ->label(__('messages.ui_90d8752e'))
                            ->placeholder(__('messages.ui_21e9f2f5'))
                            ->maxLength(100),
                        TextInput::make('email')
                            ->label(__('messages.ui_96345055'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label(__('messages.ui_1dd8263e'))
                            ->tel()
                            ->required(),
                    ])->columns(2),

                Section::make(__('messages.ui_2f706591'))
                    ->description(__('messages.ui_205e05a7'))
                    ->schema([
                        TextInput::make('hq_address')
                            ->label(__('messages.ui_ba1711d1'))
                            ->placeholder(__('messages.ui_04d7604c'))
                            ->required(),
                        TextInput::make('primary_contact_person')
                            ->label(__('messages.ui_8ed176f6'))
                            ->placeholder(__('messages.ui_858d304d'))
                            ->required(),
                        Toggle::make('enable_cross_checking')
                            ->label(__('messages.ui_1fee96b4'))
                            ->helperText(__('messages.ui_f1698170'))
                            ->default(true),
                        Select::make('status')
                            ->label(__('messages.ui_89583334'))
                            ->options(OrganizationStatus::class) // استدعاء مباشر لـ Enum حالة الحساب
                            ->required()
                            ->default(OrganizationStatus::Pending->value),
                    ])->columns(2),
            ]);
    }
}
