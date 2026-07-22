<?php

$filamentDir = __DIR__.'/app/Filament';
$langArPath = __DIR__.'/lang/ar/messages.php';
$langEnPath = __DIR__.'/lang/en/messages.php';

$arMessages = require $langArPath;
$enMessages = require $langEnPath;

$englishMap = [
    'البيانات الأساسية السيادية' => 'Core sovereign identity data',
    'التقييم الميداني الحالي للمؤسسة' => 'Current field assessment by organization',
    'الوضع الصحي والطبي الميداني للأسرة' => 'Household health and medical field status',
    'معطيات السكن والنزوح الحالي' => 'Current shelter and displacement details',
    'المؤشرات والمواصفات الخاصة بالجمعية' => 'Organization-specific indicators and attributes',
    'المعلومات الأساسية للمساعدة' => 'Basic assistance information',
    'محرك التدقيق المتقاطع ومنع الصرف المكرر (Anti-Double-Dipping Engine)' => 'Cross-check engine and anti-double-dipping',
    'محددات استحقاق الاحتياج والنقاط' => 'Need entitlement and scoring thresholds',
    'محرك الاستهداف الديموغرافي والسيادي المطور (Targeting Engine)' => 'Advanced demographic and sovereign targeting engine',
    'معايير التركيبة الأسرية والاحتياجات الميدانية الخاصة' => 'Household composition and special field needs criteria',
    'محددات الوضع الصحي والطبي الميداني' => 'Field health and medical thresholds',
    'محددات السكن ومخيمات النزوح الحالية' => 'Shelter and current displacement camp thresholds',
    'مؤشر التقدير والتحليل اللحظي' => 'Live estimation and analysis indicator',
    'الجدولة الزمنية والنشاط' => 'Scheduling and activity',
    'لا توجد حقول مخصصة حالياً. اضغط بالأسفل لإضافة أول حقل.' => 'No custom fields yet. Click below to add the first field.',
    'إضافة حقل جديد' => 'Add new field',
    'المعلومات الأساسية والترخيص' => 'Basic information and licensing',
    'العمليات الميدانية والحوكمة' => 'Field operations and governance',
    'تسجيل وإضافة مستفيد خارج كشوفات الفرز' => 'Register and add beneficiary outside screening lists',
    'شطب المواطن من كشف توزيع هذه الحزمة' => 'Remove citizen from this package distribution list',
    'معطيات المعيار وأولوية الاحتياج' => 'Criterion parameters and need priority',
    'بيانات المؤسسة الإغاثية' => 'Relief organization details',
    'بيانات مدير الحساب (المسؤول)' => 'Account manager (administrator) details',
    'تم توثيق عملية الصرف الميداني بنجاح' => 'Field distribution documented successfully',
    'تم الاستبدال بنجاح' => 'Replacement completed successfully',
    'تم شطب الفرد بنجاح وتقليص الحصص المتوفرة' => 'Individual removed and available quotas reduced',
    'تم تحديث الحقول الديناميكية للمؤسسة بنظام التقرير بنجاح.' => 'Organization dynamic report fields updated successfully.',
    'المستفيد مسجل مسبقاً بالنظام الموحد. تم جلب بياناته السيادية تلقائياً.' => 'Beneficiary already registered nationally. Sovereign data loaded automatically.',
    'النشطة حالياً' => 'Currently active',
    'المكتملة والمنتهية' => 'Completed and closed',
    'الموقوفة مؤقتاً' => 'Temporarily paused',
    'اجتماعي' => 'Social',
    'صحي/طبي' => 'Health / medical',
    'مأوى ونزوح' => 'Shelter and displacement',
    'مادي واقتصادي' => 'Financial and economic',
    'نص عادي' => 'Plain text',
    'رقم رقمي' => 'Numeric',
    'نعم / لا (Toggle)' => 'Yes / No (toggle)',
    'قائمة خيارات منسدلة' => 'Dropdown options',
    'لا تفعل (تجاوز الفحص)' => 'Disabled (skip check)',
    'استهدف فقط من استلموا سابقاً' => 'Target only prior recipients',
    'استبعد من استلموا سابقاً (منع التكرار ⚠️)' => 'Exclude prior recipients (prevent duplication)',
    'أي نوع مساعدة إغاثية' => 'Any relief assistance type',
    'مساعدات غذائية فقط' => 'Food assistance only',
    'مساعدات نقدية فقط' => 'Cash assistance only',
    'مستلزمات طبية وأدوية' => 'Medical supplies and medicines',
    'كسوة وملابس شتوية' => 'Clothing and winter wear',
    'المواطن نازح في الميدان (is_displaced)' => 'Citizen displaced in the field (is_displaced)',
    'نوع المأوى الحالي: خيمة (current_shelter_type = tent)' => 'Current shelter: tent (current_shelter_type = tent)',
    'نوع المأوى الحالي: مركز إيواء (current_shelter_type = shelter_center)' => 'Current shelter: shelter center (current_shelter_type = shelter_center)',
    'المواطن من ذوي الاحتياجات الخاصة (has_disability)' => 'Citizen with special needs (has_disability)',
    'يعاني من أمراض مزمنة (has_chronic_disease)' => 'Has chronic diseases (has_chronic_disease)',
    'لديه إصابة حرب حديثة (has_recent_injury)' => 'Recent war injury (has_recent_injury)',
    'حالة المواطن السيادية: شهيد (vital_status = martyred)' => 'Sovereign status: martyr (vital_status = martyred)',
    'حالة المواطن السيادية: مفقود (vital_status = missing)' => 'Sovereign status: missing (vital_status = missing)',
    'المواطن أنثى / احتمالية معيل أسرة (gender = female)' => 'Female citizen / potential household provider (gender = female)',
    'عائلة كبيرة العدد (أكثر من 5 أفراد)' => 'Large household (more than 5 members)',
    'بلا دخل مادي شهري نهائياً (monthly_income = 0)' => 'No monthly income (monthly_income = 0)',
    'مؤشرات اجتماعية وديموغرافية' => 'Social and demographic indicators',
    'مؤشرات طبية وصحية' => 'Medical and health indicators',
    'مؤشرات النزوح والمأوى' => 'Displacement and shelter indicators',
    'مؤشرات مادية واقتصادية' => 'Financial and economic indicators',
    'اللجنة المركزية للنظام' => 'Central system committee',
    'إضافة تلقائية بواسطة محرك الاستهداف' => 'Auto-added by targeting engine',
    'تم الاستبدال بالمستفيد البديل بقرار الباحث الميداني. السبب: ' => 'Replaced with alternate beneficiary per field officer decision. Reason: ',
];

function messageKey(string $arabicText): string
{
    return 'ui_'.substr(md5($arabicText), 0, 8);
}

function ensureMessage(string $arabicText, array &$arMessages, array &$enMessages, array $englishMap): string
{
    $key = messageKey($arabicText);

    if (! isset($arMessages[$key])) {
        $arMessages[$key] = $arabicText;
        $enMessages[$key] = $englishMap[$arabicText] ?? 'TBD';
    } elseif ($enMessages[$key] === 'TBD' && isset($englishMap[$arabicText])) {
        $enMessages[$key] = $englishMap[$arabicText];
    }

    return $key;
}

$directory = new RecursiveDirectoryIterator($filamentDir);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$patterns = [
    '/Section::make\(\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u',
    '/->modalHeading\(\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u',
    '/->truncateItemLabel\(\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u',
    '/->addActionLabel\(\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u',
    '/->title\(\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u',
    '/Filament::notify\(\s*\'[^\']+\'\s*,\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u',
];

foreach ($regex as $file => $v) {
    $content = file_get_contents($file);
    $original = $content;

    foreach ($patterns as $pattern) {
        $content = preg_replace_callback($pattern, function (array $matches) use (&$arMessages, &$enMessages, $englishMap, $pattern) {
            $arabicText = $matches[1];
            $key = ensureMessage($arabicText, $arMessages, $enMessages, $englishMap);

            if (str_starts_with($pattern, '/Section::make')) {
                return "Section::make(__('messages.{$key}'))";
            }
            if (str_starts_with($pattern, '/->modalHeading')) {
                return "->modalHeading(__('messages.{$key}'))";
            }
            if (str_starts_with($pattern, '/->truncateItemLabel')) {
                return "->truncateItemLabel(__('messages.{$key}'))";
            }
            if (str_starts_with($pattern, '/->addActionLabel')) {
                return "->addActionLabel(__('messages.{$key}'))";
            }
            if (str_starts_with($pattern, '/->title')) {
                return "->title(__('messages.{$key}'))";
            }

            return "Filament::notify('warning', __('messages.{$key}'))";
        }, $content);
    }

    // Option labels: 'key' => 'Arabic text',
    $content = preg_replace_callback(
        '/\'([a-z0-9_]+)\'\s*=>\s*\'([^\']*?\p{Arabic}[^\']*?)\'/u',
        function (array $matches) use (&$arMessages, &$enMessages, $englishMap) {
            $optionKey = $matches[1];
            $arabicText = $matches[2];
            $key = ensureMessage($arabicText, $arMessages, $enMessages, $englishMap);

            return "'{$optionKey}' => __('messages.{$key}')";
        },
        $content
    );

    if ($content !== $original) {
        file_put_contents($file, $content);
    }
}

// Fix known duplicate heading key English
$enMessages['ui_998d4b7b'] = 'Household entitlement and distribution roster';

file_put_contents($langArPath, "<?php\n\nreturn ".var_export($arMessages, true).";\n");
file_put_contents($langEnPath, "<?php\n\nreturn ".var_export($enMessages, true).";\n");

echo "Section and remaining UI strings refactored.\n";
