<x-filament-widgets::widget>
    <div class="space-y-6">

        <!-- القسم العلوي: ترحيب ذكي وإحصائيات سريعة بنظام Glassmorphism -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-8 text-white shadow-2xl border border-slate-800">
            <!-- لمسة إضاءة خلفية ناعمة -->
            <div class="absolute -right-24 -top-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">{{ __('messages.ui_1e21d5e2') }}</span>
                    <h2 class="text-3xl font-black tracking-tight mt-1 text-white">{{ __('messages.ui_26efdfa4') }}</h2>
                    <p class="text-slate-400 text-sm mt-1 max-w-xl">{{ __('messages.ui_ff2d1cef') }}</p>
                </div>

                <!-- بطاقة دائرية لنسبة الصرف الفعلي -->
                <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="32" cy="32" r="28" stroke="currentColor" class="text-white/10" stroke-width="4" fill="transparent" />
                            <circle cx="32" cy="32" r="28" stroke="currentColor" class="text-indigo-400" stroke-width="4" fill="transparent"
                                    stroke-dasharray="175" stroke-dashoffset="{{ 175 - (175 * $completionRate) / 100 }}" />
                        </svg>
                        <span class="absolute text-sm font-black text-white">{{ $completionRate }}%</span>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">{{ __('messages.ui_84b11fb3') }}</div>
                        <div class="text-lg font-bold text-white">{{ __('messages.ui_e5c95efa') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- شبكة البطاقات الثلاثية الفخمة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- بطاقة إجمالي المسجلين -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase">{{ __('messages.ui_7f9f4078') }}</span>
                        <h3 class="text-gray-500 dark:text-slate-300 text-sm font-semibold mt-1">{{ __('messages.ui_9b199315') }}</h3>
                        <p class="text-4xl font-black text-slate-900 dark:text-white mt-3">{{ number_format($totalBeneficiaries) }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl text-indigo-600 dark:text-indigo-400">
                        <x-heroicon-o-users class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></span>
                    <span>{{ __('messages.ui_607169ea') }}</span>
                </div>
            </div>

            <!-- بطاقة دورات الإغاثة النشطة -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase">{{ __('messages.ui_c3ac06e0') }}</span>
                        <h3 class="text-gray-500 dark:text-slate-300 text-sm font-semibold mt-1">{{ __('messages.ui_9419b077') }}</h3>
                        <p class="text-4xl font-black text-slate-900 dark:text-white mt-3">{{ number_format($activePackagesCount) }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 rounded-xl text-emerald-600 dark:text-emerald-400">
                        <x-heroicon-o-gift class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                    <x-heroicon-m-check-circle class="w-4 h-4" />
                    <span>{{ __('messages.ui_73a29c4e') }}</span>
                </div>
            </div>

            <!-- بطاقة عمليات الصرف الميداني الموثقة -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 dark:bg-amber-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block uppercase">{{ __('messages.ui_e1429916') }}</span>
                        <h3 class="text-gray-500 dark:text-slate-300 text-sm font-semibold mt-1">{{ __('messages.ui_3b0362b5') }}</h3>
                        <p class="text-4xl font-black text-slate-900 dark:text-white mt-3">{{ number_format($deliveredCount) }}</p>
                    </div>
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/50 rounded-xl text-amber-600 dark:text-amber-400">
                        <x-heroicon-o-check-badge class="w-6 h-6" />
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-400">
                    {{ __('messages.ui_7da29198') }} <span class="font-bold text-amber-600 dark:text-amber-400">{{ number_format($pendingCount) }}</span> {{ __('messages.ui_81331bc6') }}
                </div>
            </div>

        </div>

        <!-- قسم التحليلات المتقدمة: منحني توزيع الاحتياج ومؤشرات السلامة والنزوح -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- مؤشر الأولوية الإنسانية بنسب تقدم بصرية فائقة الوضوح -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <x-heroicon-o-funnel class="w-5 h-5 text-indigo-500" />
                    {{ __('messages.ui_c0b7a681') }}
                </h4>

                <div class="space-y-5">
                    <!-- الفئة الأولى: حرجة جداً -->
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                {{ __('messages.ui_d77e58e9') }}
                            </span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ number_format($criticalCount) }} {{ __('messages.ui_01ac7e97') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-full rounded-full"
                                 style="width: {{ $totalBeneficiaries > 0 ? ($criticalCount / $totalBeneficiaries) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- الفئة الثانية: متوسطة -->
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-semibold text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                {{ __('messages.ui_c391777b') }}
                            </span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ number_format($mediumCount) }} {{ __('messages.ui_01ac7e97') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-500 to-yellow-500 h-full rounded-full"
                                 style="width: {{ $totalBeneficiaries > 0 ? ($mediumCount / $totalBeneficiaries) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- الفئة الثالثة: مستقرة -->
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                {{ __('messages.ui_0428161f') }}
                            </span>
                            <span class="font-bold text-slate-900 dark:text-white">{{ number_format($lowCount) }} {{ __('messages.ui_01ac7e97') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full rounded-full"
                                 style="width: {{ $totalBeneficiaries > 0 ? ($lowCount / $totalBeneficiaries) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بطاقة النصائح والمطابقة الفورية لمنع الهدر المالي المزدوج -->
            <div class="bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-950 p-6 rounded-2xl border border-indigo-900/40 text-white flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-indigo-400 mb-2 flex items-center gap-1.5">
                        <x-heroicon-m-shield-check class="w-5 h-5" />
                        {{ __('messages.ui_80bf175b') }}
                    </h4>
                    <p class="text-xs text-slate-300 leading-relaxed mt-2">
                        {{ __('messages.ui_473a8c5d') }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-indigo-900/60 text-xs text-slate-400">
                    💡 {{ __('messages.ui_c13c9c33') }}
                </div>
            </div>

        </div>

    </div>
</x-filament-widgets::widget>
