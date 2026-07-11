<div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 text-center">
    <div class="text-xs text-gray-500 font-medium mb-1">العائلات المستهدفة المطابقة حالياً:</div>
    <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400 mb-3 animate-pulse">
        {{ number_format($count) }} <span class="text-sm font-normal text-gray-400">عائلة</span>
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
            الكمية المتوفرة لدى الجمعية (<span class="font-bold">{{ $quantity }} حصة</span>) تغطي حوالي
            <span class="font-extrabold {{ $colorClass }}">{{ number_format($coverage, 1) }}%</span> من إجمالي الأسر المحتاجة والمطابقة لهذه التصفية في الميدان.
        </p>
    @else
        <div class="text-xs text-amber-600 dark:text-amber-400 font-medium leading-relaxed bg-amber-50 dark:bg-amber-950/20 p-2 rounded-lg">
            ⚠️ يرجى تحديد إجمالي عدد الحصص المتوفرة للتوزيع لعرض مؤشر التغطية الفعلي.
        </div>
    @endif
</div>
