<x-filament-widgets::widget>
    <div class="space-y-6">

        <!-- القسم العلوي السيادي: واجهة التحكم والسيطرة الموحدة لغزة -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-8 text-white shadow-2xl border border-slate-800">
            <!-- خلفيات إضاءة ذكية ناعمة تليق بالأدمن -->
            <div class="absolute -right-32 -top-32 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-32 -bottom-32 w-[500px] h-[500px] bg-emerald-600/5 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">{{ __('messages.ui_57cb8c0b') }}</span>
                    </div>
                    <h2 class="text-3xl font-black tracking-tight mt-2 text-white">{{ __('messages.ui_58ba52e5') }}</h2>
                    <p class="text-slate-400 text-sm mt-1 max-w-2xl">{{ __('messages.ui_886f00b9') }}</p>
                </div>

                <!-- نسبة الإنجاز والمساندة الوطنية الشاملة -->
                <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="32" cy="32" r="28" stroke="currentColor" class="text-white/10" stroke-width="4" fill="transparent" />
                            <circle cx="32" cy="32" r="28" stroke="currentColor" class="text-emerald-400" stroke-width="4" fill="transparent"
                                    stroke-dasharray="175" stroke-dashoffset="{{ 175 - (175 * $nationalCompletionRate) / 100 }}" />
                        </svg>
                        <span class="absolute text-sm font-black text-white">{{ $nationalCompletionRate }}%</span>
                    </div>
                    <div>
                        <div class="text-xs text-slate-400">{{ __('messages.ui_2f99e83d') }}</div>
                        <div class="text-lg font-bold text-white">{{ __('messages.ui_757ba075') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- شبكة المؤشرات الاستراتيجية السيادية -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <!-- بطاقة السجل الوطني الموحد -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">{{ __('messages.ui_a2431bdf') }}</span>
                        <h3 class="text-gray-600 dark:text-slate-300 text-xs font-semibold mt-0.5">{{ __('messages.ui_32bb89f2') }}</h3>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-3">{{ number_format($totalBeneficiaries) }}</p>
                    </div>
                    <div class="p-2.5 bg-indigo-50 dark:bg-indigo-950/50 rounded-xl text-indigo-600 dark:text-indigo-400">
                        <x-heroicon-o-users class="w-5.5 h-5.5" />
                    </div>
                </div>
                <div class="mt-4 text-[11px] text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('messages.ui_9f864497') }} <span class="font-bold text-slate-700 dark:text-slate-200">{{ number_format($aliveCount) }}</span></span>
                </div>
            </div>

            <!-- بطاقة التوثيق الميداني للشهداء والمفقودين -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 dark:bg-rose-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">{{ __('messages.ui_f4733925') }}</span>
                        <h3 class="text-gray-600 dark:text-slate-300 text-xs font-semibold mt-0.5">{{ __('messages.ui_c0ad24f2') }}</h3>
                        <p class="text-3xl font-black text-rose-600 dark:text-rose-400 mt-3">{{ number_format($martyredCount) }}</p>
                    </div>
                    <div class="p-2.5 bg-rose-50 dark:bg-rose-950/50 rounded-xl text-rose-600 dark:text-rose-400">
                        <x-heroicon-o-shield-exclamation class="w-5.5 h-5.5" />
                    </div>
                </div>
                <div class="mt-4 text-[11px] text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span>{{ __('messages.ui_1ebae0b6') }} <span class="font-bold text-amber-600">{{ number_format($missingCount) }}</span></span>
                </div>
            </div>

            <!-- بطاقة الجمعيات الشريكة والترخيص -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">{{ __('messages.ui_59af946f') }}</span>
                        <h3 class="text-gray-600 dark:text-slate-300 text-xs font-semibold mt-0.5">{{ __('messages.ui_ca8f73f4') }}</h3>
                        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-3">{{ number_format($approvedOrgs) }}</p>
                    </div>
                    <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-xl text-emerald-600 dark:text-emerald-400">
                        <x-heroicon-o-building-office-2 class="w-5.5 h-5.5" />
                    </div>
                </div>
                <div class="mt-4 text-[11px] text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    <span>{{ __('messages.ui_167c57a4') }} <span class="font-bold text-slate-700 dark:text-slate-200">{{ $pendingOrgs }} {{ __('messages.ui_01e1f2bc') }}</span></span>
                </div>
            </div>

            <!-- بطاقة إجمالي الصرف الموحد ومحاربة التكرار -->
            <div class="relative overflow-hidden group bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 dark:bg-amber-950/20 rounded-full transition-all group-hover:scale-110"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">{{ __('messages.ui_df608652') }}</span>
                        <h3 class="text-gray-600 dark:text-slate-300 text-xs font-semibold mt-0.5">{{ __('messages.ui_6783b790') }}</h3>
                        <p class="text-3xl font-black text-amber-600 dark:text-amber-400 mt-3">{{ number_format($deliveredCount) }}</p>
                    </div>
                    <div class="p-2.5 bg-amber-50 dark:bg-amber-950/50 rounded-xl text-amber-600 dark:text-amber-400">
                        <x-heroicon-o-check-badge class="w-5.5 h-5.5" />
                    </div>
                </div>
                <div class="mt-4 text-[11px] text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>{{ __('messages.ui_08dce822') }} <span class="font-bold text-amber-600">{{ number_format($pendingDistributions) }} {{ __('messages.ui_26828071') }}</span></span>
                </div>
            </div>

        </div>

        <!-- تحليل موازين التدخل ونطاق تأثير الحزم الإغاثية -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- توزيع أنواع الحزم الفعالة في الميدان حالياً -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <x-heroicon-o-chart-pie class="w-5 h-5 text-indigo-500" />
                    {{ __('messages.ui_db69f7d7') }}
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- المساعدات الغذائية -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-emerald-500"></span>
                                {{ __('messages.ui_4778df58') }}
                            </span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ $foodPackagesCount }} {{ __('messages.ui_e3d873ce') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $foodPackagesCount > 0 ? min(100, ($foodPackagesCount / max(1, $foodPackagesCount + $cashPackagesCount + $medicalPackagesCount + $clothingPackagesCount)) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- المساعدات النقدية والمالية -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-indigo-500"></span>
                                {{ __('messages.ui_adb626f6') }}
                            </span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ $cashPackagesCount }} {{ __('messages.ui_e3d873ce') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $cashPackagesCount > 0 ? min(100, ($cashPackagesCount / max(1, $foodPackagesCount + $cashPackagesCount + $medicalPackagesCount + $clothingPackagesCount)) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- المساعدات الطبية -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-rose-500"></span>
                                {{ __('messages.ui_b17c37c1') }}
                            </span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ $medicalPackagesCount }} {{ __('messages.ui_e3d873ce') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-rose-500 h-full rounded-full" style="width: {{ $medicalPackagesCount > 0 ? min(100, ($medicalPackagesCount / max(1, $foodPackagesCount + $cashPackagesCount + $medicalPackagesCount + $clothingPackagesCount)) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- كسوة شتاء وملابس -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded bg-amber-500"></span>
                                {{ __('messages.ui_2ad555f0') }}
                            </span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ $clothingPackagesCount }} {{ __('messages.ui_e3d873ce') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $clothingPackagesCount > 0 ? min(100, ($clothingPackagesCount / max(1, $foodPackagesCount + $cashPackagesCount + $medicalPackagesCount + $clothingPackagesCount)) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بطاقة ميثاق النزاهة والحظر الاستباقي المركزي للجمعيات -->
            <div class="bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-950 p-6 rounded-2xl border border-indigo-900/40 text-white flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-indigo-400 mb-2 flex items-center gap-1.5">
                        <x-heroicon-m-lock-closed class="w-5 h-5 text-indigo-400" />
                        {{ __('messages.ui_7ba771a8') }}
                    </h4>
                    <p class="text-xs text-slate-300 leading-relaxed mt-2">
                        {{ __('messages.ui_c1051b86') }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-indigo-900/60 text-xs text-slate-400 flex items-center justify-between">
                    <span>{{ __('messages.ui_1d440215') }} <span class="font-bold text-emerald-400">{{ __('messages.ui_5e4335cc') }}</span></span>
                    <span class="text-[10px] bg-indigo-500/10 px-2.5 py-1 rounded border border-indigo-500/20">v2.4.0-Harness</span>
                </div>
            </div>

        </div>

    </div>
</x-filament-widgets::widget>
