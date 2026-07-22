<?php

return [
    'AssistancePackageStatus' => [
        'active' => 'نشط وجاهز للصرف الميداني',
        'completed' => 'تم التوزيع بنجاح وإغلاق الدورة',
        'paused' => 'موقوف مؤقتاً لأسباب تشغيلية',
    ],
    'AssistancePackageType' => [
        'food' => 'سلة غذائية / معلبات / خضار',
        'cash' => 'قسيمة نقدية (شيكل)',
        'medical' => 'مستلزمات طبية وأدوية',
        'clothing' => 'كسوة شتاء / ملابس وأغطية',
    ],
    'CurrentShelterType' => [
        'tent' => 'خيمة قماشية / عازل نايلون بمخيمات النزوح',
        'shelter_center' => 'مركز إيواء جماعي (مدرسة، مشفى، مرفق عام)',
        'rent_apartment' => 'شقة مستأجرة (توزيع نفقات سكن)',
        'host_family' => 'مستضاف لدى أقارب / أصدقاء بالمنزل',
        'home' => 'المنزل الأصلي للمواطن (غير مدمر بالكامل)',
    ],
    'DisabilityType' => [
        'physical' => 'إعاقة حركية / جسدية',
        'visual' => 'إعاقة بصرية / كف بصر',
        'hearing' => 'إعاقة سمعية / صمم',
        'mental' => 'إعاقة ذهنية / عقلية',
        'sensory' => 'إعاقة نطق وتخاطب / حسية',
        'multiple' => 'إعاقات متعددة مركبة',
    ],
    'DistributionStatus' => [
        'pending' => 'قيد الانتظار / لم يستلم',
        'delivered' => 'تم التسليم فعلياً للأسرة',
        'cancelled' => 'تم الإلغاء / الاستبدال الميداني',
    ],
    'Gender' => [
        'male' => 'ذكر',
        'female' => 'أنثى',
    ],
    'InjurySeverity' => [
        'critical' => 'إصابة حرجة جداً (أولوية قصوى)',
        'moderate' => 'إصابة متوسطة (أولوية ثانوية)',
        'light' => 'إصابة طفيفة (مستقرة)',
    ],
    'MaritalStatus' => [
        'married' => 'متزوج/ة',
        'single' => 'أعزب/عزباء',
        'widowed' => 'أرمل/ة',
        'divorced' => 'مطلق/ة',
    ],
    'OrganizationStatus' => [
        'pending' => 'قيد الانتظار والتدقيق الميداني',
        'approved' => 'مفعّل ومصادق عليه رسمياً',
        'suspended' => 'موقف مؤقتاً (مخالف للسياسات)',
    ],
    'PolicyCategory' => [
        'social' => 'مؤشرات اجتماعية وديموغرافية',
        'health' => 'مؤشرات طبية وصحية',
        'shelter' => 'مؤشرات النزوح والمأوى',
        'financial' => 'مؤشرات مادية واقتصادية',
    ],
    'PolicyKey' => [
        'is_displaced' => 'المواطن نازح في الميدان (is_displaced)',
        'shelter_tent' => 'نوع المأوى الحالي: خيمة (current_shelter_type = tent)',
        'shelter_center' => 'نوع المأوى الحالي: مركز إيواء (current_shelter_type = shelter_center)',
        'has_disability' => 'المواطن من ذوي الاحتياجات الخاصة (has_disability)',
        'has_chronic_disease' => 'يعاني من أمراض مزمنة (has_chronic_disease)',
        'has_recent_injury' => 'لديه إصابة حرب حديثة (has_recent_injury)',
        'vital_status_martyred' => 'حالة المواطن السيادية: شهيد (vital_status = martyred)',
        'vital_status_missing' => 'حالة المواطن السيادية: مفقود (vital_status = missing)',
        'gender_female' => 'المواطن أنثى / احتمالية معيل أسرة (gender = female)',
        'family_large' => 'عائلة كبيرة العدد (أكثر من 5 أفراد)',
        'no_income' => 'بلا دخل مادي شهري نهائياً (monthly_income = 0)',
    ],
    'ShelterCondition' => [
        'bad' => 'مهترئ / غير صالح للاستخدام الإنساني',
        'acceptable' => 'مقبول / يحتاج لصيانات خفيفة',
        'good' => 'سليم / بحالة جيدة جداً',
    ],
    'SurveyStatus' => [
        'active' => 'نشط ومعتمد ميدانياً',
        'archived' => 'مؤرشف (سجل تاريخي قديم)',
        'conflict' => 'متعارض (يتطلب مراجعة وتدقيق ميداني)',
    ],
    'VitalStatus' => [
        'alive' => 'على قيد الحياة',
        'martyred' => 'شهيد (رحمه الله)',
        'missing' => 'مفقود (لم يُعثر عليه)',
    ],
];
