export default function Footer() {
  return (
    <footer className="bg-linear-to-b from-teal-900 to-neutral-900 text-gray-200 pt-16 pb-8 border-t border-teal-900/50">
      <div className="container mx-auto px-6 text-center">
        <h4 className="text-xl font-black tracking-wider mb-2 text-white">
          مشروع تخرج 2
        </h4>
        <p className="text-teal-400 font-bold text-sm mb-8">
          كلية هندسة البرمجيات و الذكاء الاصطناعي • جامعة فلسطين
        </p>

        <div className="max-w-3xl mx-auto bg-teal-900/20 rounded-2xl p-6 border border-teal-800/30 mb-4">
          <span className="text-xs text-teal-300 font-medium block mb-3">
            تحت إشراف الدكتورة:
          </span>
          <p className="font-black text-white text-base mb-4">
            د. فاطمة الرباعي
          </p>

          <span className="text-xs text-teal-300 font-medium block mb-2">
            فريق العمل والتطوير من الطلاب:
          </span>
          <div className="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs md:text-sm font-bold text-gray-300">
            <span>أمجد فياض</span>
            <span>عبدالله النبريصي</span>
            <span>محمد الأشقر</span>
            <span>لمياء بريكة</span>
            <span>محمود أبو مشايخ</span>
          </div>
        </div>
      </div>
    </footer>
  );
}
