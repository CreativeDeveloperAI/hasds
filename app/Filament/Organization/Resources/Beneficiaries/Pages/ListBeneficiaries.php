<?php

namespace App\Filament\Organization\Resources\Beneficiaries\Pages;

use App\Filament\Organization\Resources\Beneficiaries\BeneficiaryResource;
use App\Models\CustomFieldDefinition;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Loader\ContainerLoader;
use Tiptap\Core\Schema;

class ListBeneficiaries extends ListRecords
{
    protected static string $resource = BeneficiaryResource::class;

    protected function getHeaderActions(): array
    {
        $tenantId = Filament::getTenant()?->id;

        return [
            // زر الحقول الديناميكية الذكي
        Action::make('configure_custom_fields')
                ->label('ضبط الحقول الإضافية')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                // 1. جلب البيانات الحالية وتعبئتها داخل الـ Modal عند الضغط
                ->mountUsing(function ($form) use ($tenantId) {
                    $fields = CustomFieldDefinition::where('organization_id', $tenantId)->get()->toArray();
                    $form->fill([
                        'definitions' => $fields,
                    ]);
                })
                // 2. تصميم واجهة التحكم داخل الـ Modal المنبثق
                ->schema([
                   Repeater::make('definitions')
                        ->label('حقول المؤسسة المخصصة')
                        ->truncateItemLabel('لا توجد حقول مخصصة حالياً. اضغط بالأسفل لإضافة أول حقل.')
                        ->addActionLabel('إضافة حقل جديد')
                        ->schema([
                           TextInput::make('field_label')
                                ->label('اسم الحقل (يظهر للموظف)')
                                ->required()
                                ->placeholder('مثال: احتياج الشتاء، فصيلة الدم')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set) {
                                    // توليد المفتاح البرمجي تلقائياً بناءً على الاسم المدخل لحفظه في الـ JSON
                                    $slug = Str::slug($state, '_');
                                    $set('field_key', $slug ?: 'field_' . rand(10, 99));
                                }),

                           Hidden::make('field_key')->required(),

                        Select::make('field_type')
                                ->label('نوع الحقل')
                                ->options([
                                    'text' => 'نص عادي',
                                    'number' => 'رقم رقمي',
                                    'boolean' => 'نعم / لا (Toggle)',
                                    'select' => 'قائمة خيارات منسدلة',
                                ])
                                ->required()
                                ->default('text')
                                ->live(),

                            // يظهر حقل إدخال الخيارات فقط إذا اختار المدير نوع الحقل "قائمة خيارات"
                        TagsInput::make('options')
                                ->label('خيارات القائمة المنسدلة')
                                ->placeholder('اكتب الخيار واضغط Enter')
                                ->visible(fn (Get $get) => $get('field_type') === 'select')
                                ->required(),

                        Toggle::make('is_required')
                                ->label('حقل إجباري عند التعبئة؟')
                                ->default(false),
                        ])
                        ->columns(2)
                ])
                // 3. معالجة الحفظ الذكي عند الضغط على زر "حفظ" في الـ Modal
                ->action(function (array $data) use ($tenantId) {
                    // مصفوفة لتتبع الـ IDs الموجودة حتى نحذف ما قام المستخدم بحذفه في الواجهة
                    $keptIds = [];

                    foreach ($data['definitions'] as $fieldData) {
                        $field = CustomFieldDefinition::updateOrCreate(
                            [
                                'organization_id' => $tenantId,
                                'field_key' => $fieldData['field_key']
                            ],
                            [
                                'field_label' => $fieldData['field_label'],
                                'field_type' => $fieldData['field_type'],
                                'options' => $fieldData['options'] ?? null,
                                'is_required' => $fieldData['is_required'] ?? false,
                            ]
                        );
                        $keptIds[] = $field->id;
                    }

                    // حذف الحقول التي قام المدير بإزالتها من الـ Repeater
                    CustomFieldDefinition::where('organization_id', $tenantId)
                        ->whereNotIn('id', $keptIds)
                        ->delete();

                    Notification::make()
                        ->success()
                        ->title('تم تحديث الحقول الديناميكية للمؤسسة بنظام التقرير بنجاح.')
                    ->send();
                })
                ->slideOver(), // حركة انسيابية تفتح الـ Modal من الجانب بشكل عصري ومريح

            // زر إضافة مستفيد الافتراضي
            CreateAction::make()->label('إضافة مستفيد جديد'),
        ];
    }
}
