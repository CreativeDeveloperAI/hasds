import { motion } from "framer-motion";

export default function Hero() {
  return (
    <section
      id="hero"
      className="pt-32 pb-20 md:pt-40 md:pb-28 relative overflow-hidden bg-linear-to-b from-teal-50/40 via-white to-white"
    >
      <div className="absolute top-0 left-1/4 w-72 h-72 md:w-96 md:h-96 bg-teal-200/30 rounded-full blur-3xl -z-10 animate-pulse"></div>
      <div className="absolute bottom-10 right-1/4 w-72 h-72 md:w-96 md:h-96 bg-emerald-200/20 rounded-full blur-3xl -z-10"></div>

      <div className="container mx-auto px-6 text-center">
        <motion.h1
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.1 }}
          className="text-3xl sm:text-4xl md:text-6xl font-black text-gray-950 mb-6 leading-tight max-w-4xl mx-auto"
        >
          Humanitarian Aid Smart <br className="hidden md:block" /> Distribution
          System
          <span className="bg-linear-to-r from-teal-600 via-emerald-500 to-teal-700 bg-clip-text text-transparent block md:inline md:mr-3 font-extrabold">
            (HASDS)
          </span>
        </motion.h1>

        <motion.p
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.3 }}
          className="max-w-3xl mx-auto text-base md:text-lg text-gray-600 leading-relaxed font-medium"
        >
          منصة ويب ذكية ومتكاملة لإدارة وتوزيع المساعدات الإنسانية الإغاثية في
          قطاع غزة، تعمل على أتمتة الإجراءات بالذكاء الاصطناعي لمكافحة الهدر،
          منع تكرار الصرف، وضمان أعلى معايير النزاهة والعدالة الميدانية.
        </motion.p>
      </div>
    </section>
  );
}
