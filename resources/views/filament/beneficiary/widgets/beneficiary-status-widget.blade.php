<x-filament-widgets::widget>
    <div class="space-y-6">
        <!-- الرأس الأساسي: البيانات السيادية -->
        <div class="p-6 rounded-2xl bg-gradient-to-tr from-indigo-900 to-purple-800 text-white shadow-xl">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">{{ __('messages.ui_9196242d', ['name' => $beneficiary->full_name]) }}</h2>
                    <p class="text-purple-200 mt-2 font-mono text-lg">{{ __('messages.ui_68f906e3') }} {{ $beneficiary->national_id }}</p>
                </div>
                <div class="text-right">
                    <span class="px-4 py-1.5 rounded-full bg-white/20 border border-white/30 text-sm font-semibold">
                        {{ __('messages.ui_1a424711') }} {{ $beneficiary->vital_status->getLabel() }}
                    </span>
                    <p class="text-xs text-purple-300 mt-2">{{ __('messages.ui_e5d6151d') }} {{ $beneficiary->marital_status->getLabel() }}</p>
                </div>
            </div>
        </div>

        <!-- تكرار بيانات المؤسسات الشريكة -->
        @foreach($beneficiary->organizations as $org)
            <div class="border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden bg-white dark:bg-gray-900 shadow-sm">
                <!-- شريط عنوان المؤسسة -->
                <div class="bg-gray-50 dark:bg-gray-800 p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $org->name }}</h3>
                        <p class="text-sm text-gray-500">{{ __('messages.ui_04b4b766') }}</p>
                    </div>
                    <div class="text-center px-4 py-2 bg-white dark:bg-gray-900 rounded-xl shadow-inner border border-gray-100 dark:border-gray-700">
                        <div class="text-[10px] uppercase text-gray-400 font-bold">{{ __('messages.ui_870459a7') }}</div>
                        <div class="text-3xl font-black text-indigo-600">{{ number_format($org->pivot->priority_score ?? 0, 0) }}</div>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- تفاصيل الحالة الاجتماعية والميدانية -->
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-heroicon-o-home class="w-5 h-5 text-indigo-500" /> {{ __('messages.ui_72445020') }}
                            </h4>
                            <ul class="text-sm space-y-2 text-gray-600 dark:text-gray-400">
                                <li>{{ __('messages.ui_3f3f7de9') }} <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $org->pivot->is_displaced ? __('messages.ui_f6d5f138') : __('messages.ui_4827979f') }}</span></li>
                                <li>{{ __('messages.ui_63efa731') }} <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $org->pivot->current_shelter_type?->getLabel() ?? __('messages.ui_cd09c30d') }}</span></li>
                                <li>{{ __('messages.ui_16a1f166') }} <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $org->pivot->current_displacement_location ?? __('messages.ui_5883c355') }}</span></li>
                            </ul>
                        </div>
                        <div class="space-y-4">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <x-heroicon-o-users class="w-5 h-5 text-indigo-500" /> {{ __('messages.ui_b5ef82fb') }}
                            </h4>
                            <ul class="text-sm space-y-2 text-gray-600 dark:text-gray-400">
                                <li>{{ __('messages.ui_2d2c95be') }} <span class="font-semibold">{{ $org->pivot->family_members_count }}</span></li>
                                <li>{{ __('messages.ui_70f44ec1') }} <span class="font-semibold">{{ $org->pivot->children_under_5_count }}</span></li>
                                <li>{{ __('messages.ui_2a59f591') }} <span class="font-semibold">{{ $org->pivot->elderly_count }}</span></li>
                                <li>{{ __('messages.ui_68b38b9f') }} <span class="font-semibold">{{ $org->pivot->pregnant_or_lactating_count }}</span></li>
                            </ul>
                        </div>
                        <div class="lg:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.ui_c6fa3f60') }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @if($org->pivot->has_disability) <span class="px-3 py-1 bg-red-100 text-red-800 rounded-lg text-xs font-bold">{{ __('messages.ui_fc21e4e1') }} {{ $org->pivot->disability_type?->getLabel() }}</span> @endif
                                @if($org->pivot->has_chronic_disease) <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-lg text-xs font-bold">{{ __('messages.ui_018187d3') }}</span> @endif
                                @if($org->pivot->has_recent_injury) <span class="px-3 py-1 bg-rose-100 text-rose-800 rounded-lg text-xs font-bold">{{ __('messages.ui_168ea4a5') }} {{ $org->pivot->injury_severity?->getLabel() }}</span> @endif
                            </div>
                        </div>
                    </div>

                    <!-- سجل المساعدات -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                            <x-heroicon-o-gift class="w-5 h-5 text-green-500" /> {{ __('messages.ui_9d40c75b') }}
                        </h4>
                        @php
                            $orgDistributions = $beneficiary->distributions()
                                ->whereHas('assistancePackage', fn($q) => $q->where('organization_id', $org->id))
                                ->with('assistancePackage')
                                ->get();
                        @endphp

                        @if($orgDistributions->isNotEmpty())
                            <ul class="space-y-3">
                                @foreach($orgDistributions as $dist)
                                    <li class="bg-white dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                        <div class="font-bold text-sm text-green-700 dark:text-green-400">{{ $dist->assistancePackage->name }}</div>
                                        <div class="text-[11px] text-gray-500 mt-1">{{ __('messages.ui_bb58dd69') }} {{ $dist->delivered_at?->format('Y-m-d') }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-400 italic">{{ __('messages.ui_6c3b351a') }}</p>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 text-[11px] text-gray-400">
                    {{ __('messages.ui_5243d0a6') }} {{ $org->pivot->surveyed_at?->diffForHumans() }}
                </div>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
