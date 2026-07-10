<?php

namespace App\Filament\Beneficiary\Pages\Auth;

use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\Login;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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
                    ->label('رقم الهوية الوطنية')
                    ->required()
                    ->numeric()
                    ->autocomplete(),

                DatePicker::make('password')
                    ->label('تاريخ الميلاد (كلمة المرور)')
                    ->required()
                    ->placeholder('اختر أو اكتب تاريخ ميلادك')
                    ->displayFormat('Y-m-d'), // التنسيق القياسي المعتمد

                $this->getRememberFormComponent(),
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
}
