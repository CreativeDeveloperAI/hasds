<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التوزيع الذكي للمساعدات الإنسانية (HASDS)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-50 via-gray-50 to-gray-100 flex flex-col min-h-screen justify-between">

<header class="glass-header sticky top-0 z-50 border-b border-gray-200/60 py-4 px-6 text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-center gap-2 mb-1">
        <i data-lucide="shield-check" class="w-7 h-7 text-emerald-600 animate-pulse"></i>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">نظام التوزيع الذكي للمساعدات الإنسانية (HASDS)</h1>
    </div>
    <p class="text-xs font-semibold text-slate-600 tracking-wide uppercase">Humanitarian Aid Smart Distribution System</p>
    <p class="text-xs font-medium text-slate-500 max-w-2xl mx-auto leading-relaxed mt-1">المنصة المركزية لإستحقاق المساعدات الغذائية والطبية والنقدية بقطاع غزة - القضاء على التوزيع المكرر وتعزيز شفافية الاستهداف</p>
</header>

<main class="max-w-6xl mx-auto px-6 py-12 flex-grow flex flex-col justify-center items-center w-full">
    <div class="text-center mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                بوابة توجيه منظمات المساعدات والمسؤولين
            </span>
        <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">مرحباً بك في منصة HASDS</h2>
        <p class="text-slate-500 mt-3 text-base max-w-xl mx-auto">نظام متكامل يغطي عمليات التسجيل، التقييم الميداني، تحديد أولويات المستفيدين بناءً على نقاط الاستحقاق المعيارية، والتقارير التفصيلية.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl">

        <div class="group bg-white rounded-3xl shadow-[0_10px_30px_-5px_rgba(0,0,0,0.03)] border border-slate-100 p-8 flex flex-col justify-between transition-all duration-500 hover:shadow-[0_20px_40px_-5px_rgba(37,99,235,0.1)] hover:-translate-y-2 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-50/40 rounded-full blur-2xl group-hover:bg-blue-100/50 transition-all duration-500"></div>

            <div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100/60 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-blue-200/30 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="sliders" class="w-7 h-7 stroke-[1.8]"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-blue-600 transition-colors">الإدارة العامة للنظام</h3>
                <p class="text-slate-500 text-sm leading-relaxed">بوابة الإدارة المركزية لمراجعة واعتماد طلبات المنظمات والجمعيات الشريكة، حوكمة وتتبع الفجوات الإغاثية، وإصدار الإحصائيات الشاملة لعمليات التوزيع الموحدة.</p>
            </div>
            <div class="mt-8">
                <a href="{{ url('/admin/login') }}" class="group/btn flex items-center justify-center gap-2 w-full bg-slate-900 hover:bg-blue-600 text-white font-medium py-3.5 px-4 rounded-2xl shadow-sm transition-all duration-300">
                    <span>تسجيل دخول الإدارة</span>
                    <i data-lucide="arrow-left" class="w-4 h-4 transition-transform duration-300 group-hover/btn:-translate-x-1"></i>
                </a>
            </div>
        </div>

        <div class="group bg-white rounded-3xl shadow-[0_10px_30px_-5px_rgba(0,0,0,0.03)] border border-slate-100 p-8 flex flex-col justify-between transition-all duration-500 hover:shadow-[0_20px_40px_-5px_rgba(13,148,136,0.1)] hover:-translate-y-2 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-teal-50/40 rounded-full blur-2xl group-hover:bg-teal-100/50 transition-all duration-500"></div>

            <div>
                <div class="w-14 h-14 bg-gradient-to-br from-teal-50 to-teal-100/60 text-teal-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-teal-200/30 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="building-2" class="w-7 h-7 stroke-[1.8]"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-teal-600 transition-colors">منظمات ومسؤولي المساعدات</h3>
                <p class="text-slate-500 text-sm leading-relaxed">بوابة الجمعيات واللجان الميدانية لإدخال وفحص كشوفات المستفيدين بشكل متقاطع لمنع التكرار، وضبط الحقول الديناميكية المرنة حسب طبيعة الاحتياج الميداني.</p>
            </div>
            <div class="mt-8 space-y-3">
                <a href="{{ url('/organization/login') }}" class="group/btn flex items-center justify-center gap-2 w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-3.5 px-4 rounded-2xl shadow-sm shadow-teal-600/10 transition-all duration-300">
                    <span>تسجيل دخول المؤسسة</span>
                    <i data-lucide="arrow-left" class="w-4 h-4 transition-transform duration-300 group-hover/btn:-translate-x-1"></i>
                </a>
                <a href="{{ url('/organization/register') }}" class="flex items-center justify-center gap-1 text-teal-600 hover:text-teal-700 font-semibold text-sm transition py-1">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>تقديم طلب اعتماد مؤسسة جديدة</span>
                </a>
            </div>
        </div>

        <div class="group bg-white rounded-3xl shadow-[0_10px_30px_-5px_rgba(0,0,0,0.03)] border border-slate-100 p-8 flex flex-col justify-between transition-all duration-500 hover:shadow-[0_20px_40px_-5px_rgba(147,51,234,0.1)] hover:-translate-y-2 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-purple-50/40 rounded-full blur-2xl group-hover:bg-purple-100/50 transition-all duration-500"></div>

            <div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-50 to-purple-100/60 text-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-purple-200/30 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="user-check" class="w-7 h-7 stroke-[1.8]"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-purple-600 transition-colors">بوابة المستفيدين (المواطنين)</h3>
                <p class="text-slate-500 text-sm leading-relaxed">تتيح لأرباب الأسر في المناطق المستهدفة بغزة إمكانية التسجيل الذاتي، تحديث معطيات السكن والنزوح الحالية، ومتابعة حالة طلباتهم لتسريع وتيرة الاستجابة.</p>
            </div>
            <div class="mt-8">
                <a href="{{ url('/beneficiary/portal') }}" class="group/btn flex items-center justify-center gap-2 w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3.5 px-4 rounded-2xl shadow-sm shadow-purple-600/10 transition-all duration-300">
                    <span>دخول بوابة المستفيد</span>
                    <i data-lucide="arrow-left" class="w-4 h-4 transition-transform duration-300 group-hover/btn:-translate-x-1"></i>
                </a>
            </div>
        </div>

    </div>
</main>

<footer class="bg-white border-t border-slate-200/60 py-4 text-center text-xs font-medium text-slate-400 shadow-[0_-2px_15px_rgba(0,0,0,0.02)]">
    جميع الحقوق محفوظة © {{ date('Y') }} - نظام HASDS لحوكمة المساعدات الإنسانية وتقليل الهدر
</footer>

<script>
    lucide.createIcons();
</script>
</body>
</html>
