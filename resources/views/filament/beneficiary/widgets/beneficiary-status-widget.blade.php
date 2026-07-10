<x-filament-widgets::widget>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="grid grid-cols-1 gap-y-6">

        <div class="p-6 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-700 text-white shadow-md">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="text-xs font-semibold tracking-wide uppercase text-purple-100 mb-1">
                        البوابة الرقمية الموحدة للمستفيدين (HASDS)
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-white">
                        مرحباً، {{ $beneficiary->full_name }}
                    </h2>
                    <p class="text-sm text-purple-200 mt-1">
                        رقم الهوية الوطنية: <span class="font-mono bg-black/20 px-2 py-0.5 rounded text-white font-bold">{{ $beneficiary->national_id }}</span>
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur px-4 py-3 rounded-lg text-center border border-white/10 min-w-[140px]">
                    <div class="text-xs text-purple-100 font-medium">مؤشر أولوية الاحتياج</div>
                    <div class="text-3xl font-black text-yellow-300 mt-0.5">
                        {{ number_format($pivot?->priority_score ?? 0, 0) }}<span class="text-sm text-white/80">/100</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <x-filament::section icon="heroicon-o-check-circle" icon-color="purple">
                <x-slot name="heading">حالة الاستهداف الحالية</x-slot>

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-2 text-sm">
                        <span class="text-gray-500">تصنيف الأولوية:</span>
                        @if(($pivot?->priority_score ?? 0) >= 75)
                            <span class="font-bold text-red-600 dark:text-red-400">حرجة (أولوية قصوى)</span>
                        @elseif(($pivot?->priority_score ?? 0) >= 50)
                            <span class="font-bold text-amber-600 dark:text-amber-400">متوسطة الاحتياج</span>
                        @else
                            <span class="font-bold text-green-600 dark:text-green-400">مستقرة نسبياً</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm pt-1">
                        <span class="text-gray-500">الجهة المقيمة:</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $organization_name }}</span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section icon="heroicon-o-home" icon-color="indigo">
                <x-slot name="heading">معطيات السكن والنزوح</x-slot>

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-2 text-sm">
                        <span class="text-gray-500">حالة السكن:</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            {{ $pivot?->is_displaced ? 'نازح في الميدان' : 'مقيم في منزله الأصلي' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm pt-1">
                        <span class="text-gray-500">نوع المأوى:</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">
                            {{ $pivot?->current_shelter_type ?: 'غير محدد' }}
                        </span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section icon="heroicon-o-users" icon-color="slate">
                <x-slot name="heading">التركيبة الاجتماعية</x-slot>

                <div class="space-y-3 pt-2">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-2 text-sm">
                        <span class="text-gray-500">أفراد الأسرة:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $pivot?->family_members_count ?? 1 }} أفراد</span>
                    </div>
                    <div class="flex justify-between items-center text-sm pt-1">
                        <span class="text-gray-500">آخر تحديث:</span>
                        <span class="text-xs text-gray-400">
                            {{ $pivot?->surveyed_at ? \Carbon\Carbon::parse($pivot->surveyed_at)->format('Y-m-d') : 'لا يوجد مسح مؤخراً' }}
                        </span>
                    </div>
                </div>
            </x-filament::section>

        </div>

        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
            💡 <strong>تنبيه للمستفيد:</strong> المعطيات والنقاط أعلاه مستخرجة مباشرة من سجلات المسح الميداني المحوكمة. في حال حدوث تغيير مفاجئ في حالتك الاجتماعية أو مكان الإيواء، يُرجى مراجعة المؤسسة الشريكة المسؤولة لتحديث الاستمارة الميدانية لضمان دقة ترتيبك في كشوفات الاستحقاق الذكية.
        </div>

    </div>
</x-filament-widgets::widget>
