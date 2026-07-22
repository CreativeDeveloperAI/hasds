<?php

namespace App\Filament\Beneficiary\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\Login;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class CustomBeneficiaryLogin extends Login
{
    use HasAuthDesignerLayout;

    protected function getAuthDesignerPageKey(): string
    {
        return 'login';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('national_id')
                    ->label(__('messages.ui_3ca30c31'))
                    ->required()
                    ->numeric()
                    ->autocomplete(),

                DatePicker::make('password')
                    ->label(__('messages.ui_81ad391d'))
                    ->required()
                    ->placeholder(__('messages.ui_4007e558'))
                    ->displayFormat('Y-m-d'), // التنسيق القياسي المعتمد
            ]);
    }

    // إخبار لارافيل أن الحقل المستخدم في الـ Auth هو national_id
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'national_id' => $data['national_id'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.national_id' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
