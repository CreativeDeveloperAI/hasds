<?php

namespace App\Filament\Pages\Auth;

use App\Models\Organization;
use App\Models\User;
use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class RegisterOrganization extends Register
{
    use HasAuthDesignerLayout;

    protected function getAuthDesignerPageKey(): string
    {
        return 'register';
    }

    /**
     * تخصيص حقول نموذج التسجيل ليحتوي على بيانات المؤسسة والمدير معاً
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([             // قسم بيانات المؤسسة
                Section::make(__('messages.ui_35be4943'))
                    ->description(__('messages.ui_f881e182'))
                    ->schema([
                        TextInput::make('organization_name')
                            ->label(__('messages.ui_436e953f'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('license_number')
                            ->required()
                            ->label(__('messages.ui_90d8752e'))
                            ->maxLength(255),
                        TextInput::make('hq_address')
                            ->label(__('messages.ui_3ff3db76'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('organization_phone')
                            ->label(__('messages.ui_aefc476f'))
                            ->tel()
                            ->required(),
                    ])->columns(1),

                // قسم بيانات مدير الحساب
                Section::make(__('messages.ui_280efa5a'))
                    ->description(__('messages.ui_efa31ead'))
                    ->schema([
                        $this->getNameFormComponent()->label(__('messages.ui_9f58298b')),
                        $this->getEmailFormComponent()->label(__('messages.ui_9f255580')),
                        $this->getPasswordFormComponent()->label(__('messages.ui_bcb75ee3')),
                        $this->getPasswordConfirmationFormComponent()->label(__('messages.ui_6a507e0a')),
                    ])->columns(1),
            ]);
    }

    /**
     * تخصيص عملية الحفظ: إنشاء المؤسسة أولاً بحالة pending ثم ربط المستخدم بها
     */
    protected function handleRegistration(array $data): Model
    {
        // 1. إنشاء المؤسسة في قاعدة البيانات
        $organization = Organization::create([
            'name' => $data['organization_name'],
            'license_number' => $data['license_number'] ?? null,
            'email' => $data['email'], // سنعتمد إيميل المدير كإيميل مراسلة للمؤسسة أيضاً
            'phone' => $data['organization_phone'],
            'hq_address' => $data['hq_address'],
            'primary_contact_person' => $data['name'],
            'status' => 'pending', // تظل قيد الانتظار حتى توافق أنت عليها
        ]);

        // 2. إنشاء المستخدم وربطه بالمؤسسة وتعيينه كمدير
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'organization_id' => $organization->id,
            'is_active' => true,
        ]);

        // إسناد دور مدير مؤسسة له (إذا كنت قد جهزت الأدوار)
        // $user->assignRole('organization_admin');

        return $user;
    }
}
