<?php

namespace App\Filament\Pages\Auth;

use App\Models\Organization;
use App\Models\User;
use Caresome\FilamentAuthDesigner\Concerns\HasAuthDesignerLayout;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
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
            Section::make('بيانات المؤسسة الإغاثية')
                ->description('أدخل معلومات الجمعية أو المبادرة لتوثيقها في النظام')
                ->schema([
                    TextInput::make('organization_name')
                        ->label('اسم المؤسسة / الجمعية')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('license_number')
                        ->required()
                        ->label('رقم الترخيص الرسمي')
                        ->maxLength(255),
                    TextInput::make('hq_address')
                        ->label('المقر الرئيسي الحالي')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('organization_phone')
                        ->label('رقم هاتف المؤسسة')
                        ->tel()
                        ->required(),
                ])->columns(1),

            // قسم بيانات مدير الحساب
            Section::make('بيانات مدير الحساب (المسؤول)')
                ->description('هذه البيانات ستستخدم لتسجيل الدخول وإدارة اللوحة')
                ->schema([
                    $this->getNameFormComponent()->label('اسم المسؤول'),
                    $this->getEmailFormComponent()->label('البريد الإلكتروني الرسمي للمسؤول'),
                    $this->getPasswordFormComponent()->label('كلمة المرور'),
                    $this->getPasswordConfirmationFormComponent()->label('تأكيد كلمة المرور'),
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
            'password' => Hash::make($data['password']),
            'organization_id' => $organization->id,
            'is_active' => true,
        ]);

        // إسناد دور مدير مؤسسة له (إذا كنت قد جهزت الأدوار)
        // $user->assignRole('organization_admin');

        return $user;
    }
}
