<div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 text-center">
    <div class="text-xs text-gray-500 font-medium mb-1">{{ __('messages.ui_3b30d70d') }}</div>
    <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400 mb-3 animate-pulse">
        {{ number_format($count) }} <span class="text-sm font-normal text-gray-400">{{ __('messages.ui_f05d2aa9') }}</span>
    </div>

    @if($quantity > 0)
        @php
            $coverage = min(100, ($quantity / max(1, $count)) * 100);
            $colorClass = $coverage >= 100 ? 'text-emerald-600' : ($coverage >= 50 ? 'text-amber-600' : 'text-red-500');
        @endphp
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-2 overflow-hidden">
            <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $coverage }}%"></div>
        </div>
        <p class="text-xs text-gray-500 leading-relaxed">
            {{ __('messages.ui_45156385') }} (<span class="font-bold">{{ $quantity }} {{ __('messages.ui_26828071') }}</span>) {{ __('messages.ui_7746e4c7') }}
            <span class="font-extrabold {{ $colorClass }}">{{ number_format($coverage, 1) }}%</span> {{ __('messages.ui_3d8ce436') }}
        </p>
    @else
        <div class="text-xs text-amber-600 dark:text-amber-400 font-medium leading-relaxed bg-amber-50 dark:bg-amber-950/20 p-2 rounded-lg">
            ⚠️ {{ __('messages.ui_7b89d17f') }}
        </div>
    @endif
</div>
