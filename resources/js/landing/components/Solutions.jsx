import { motion } from "framer-motion";
import {
  HiOutlineShieldCheck,
  HiOutlineCpuChip,
  HiOutlineClock,
} from "react-icons/hi2";

const solutions = [
  {
    icon: <HiOutlineShieldCheck className="w-7 h-7" />,
    title: "منع الإزدواجية",
    desc: "الاعتماد على قاعدة بيانات هيكلية صارمة تمنع تكرار تقديم المساعدات المتطابقة لنفس المستفيد بشكل عشوائي، مما يحد من الهدر بنسبة 95%.",
    gradient: "from-emerald-400 to-teal-500",
    iconColorRest: "rgb(16 185 129)",
    iconBgRest: "rgba(16, 185, 129, 0.1)",
  },
  {
    icon: <HiOutlineCpuChip className="w-7 h-7" />,
    title: "ترتيب الأولويات الآلي",
    desc: "دمج نماذج تعلم الآلة عبر بايثون لتحليل وتصنيف مستوى حالة العائلات بناءً على مصفوفة معايير متقدمة ومتعددة المحاور.",
    gradient: "from-blue-400 to-cyan-500",
    iconColorRest: "rgb(59 130 246)",
    iconBgRest: "rgba(59, 130, 246, 0.1)",
  },
  {
    icon: <HiOutlineClock className="w-7 h-7" />,
    title: "الإستجابة لظروف الطوارئ",
    desc: "دعم خطط التدخل السريع لمواجهة المستجدات الميدانية المفاجئة وتوليد مسارات توزيع طارئة ومعالجتها في أقل من 15 دقيقة.",
    gradient: "from-purple-400 to-indigo-500",
    iconColorRest: "rgb(168 85 247)",
    iconBgRest: "rgba(168, 85, 247, 0.1)",
  },
];

const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.1 } },
};

const entranceVariants = {
  hidden: { opacity: 0, y: 30 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: "easeOut" } },
};

const globalTransition = { duration: 0.5, ease: "easeInOut" };

const cardHoverVariants = {
  rest: { y: 0, shadow: "0 1px 3px 0 rgb(0 0 0 / 0.1)" },
  hover: { y: -8 },
};

const getIconVariants = (colorRest, bgRest) => ({
  rest: {
    scale: 1,
    backgroundColor: bgRest,
    color: colorRest,
  },
  hover: {
    scale: 1.1,
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    color: "rgb(255 255 255)",
  },
});

const titleVariants = {
  rest: { color: "rgb(3 7 18)" },
  hover: { color: "rgb(255 255 255)" },
};

const descVariants = {
  rest: { color: "rgb(107 114 128)" },
  hover: { color: "rgba(255, 255, 255, 0.85)" },
};

export default function Solutions() {
  return (
    <section id="solutions" className="py-10 bg-gray-50/30">
      <div className="container mx-auto px-6">
        <div className="text-center mb-16">
          <h2 className="text-2xl md:text-4xl font-black text-gray-950 mb-4 tracking-tight">
            المعالجة الذكية وحلول الثغرات الميدانية
          </h2>
          <p className="text-gray-500 max-w-xl mx-auto text-sm md:text-base font-medium">
            تم بناء النظام لمعالجة المشاكل الحرجة التي تواجه العمل الإغاثي
            التقليدي في قطاع غزة
          </p>
        </div>

        <motion.div
          className="grid grid-cols-1 md:grid-cols-3 gap-8"
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
        >
          {solutions.map((sol, index) => (
            <motion.div
              key={index}
              variants={entranceVariants}
              className="h-full"
            >
              <motion.div
                initial="rest"
                whileHover="hover"
                variants={cardHoverVariants}
                transition={globalTransition}
                className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl flex flex-col items-center text-center h-full relative overflow-hidden cursor-pointer group"
              >
                <div
                  className={`absolute inset-0 bg-linear-to-r ${sol.gradient} opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out z-0`}
                ></div>

                <div className="relative z-10 flex flex-col items-center h-full w-full">
                  <motion.div
                    variants={getIconVariants(
                      sol.iconColorRest,
                      sol.iconBgRest,
                    )}
                    transition={globalTransition}
                    className="w-14 h-14 rounded-xl flex items-center justify-center mb-6 shadow-sm font-semibold"
                  >
                    {sol.icon}
                  </motion.div>

                  <motion.h3
                    variants={titleVariants}
                    transition={globalTransition}
                    className="font-extrabold text-xl mb-3"
                  >
                    {sol.title}
                  </motion.h3>

                  <motion.p
                    variants={descVariants}
                    transition={globalTransition}
                    className="text-sm leading-relaxed font-medium grow"
                  >
                    {sol.desc}
                  </motion.p>
                </div>
              </motion.div>
            </motion.div>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
