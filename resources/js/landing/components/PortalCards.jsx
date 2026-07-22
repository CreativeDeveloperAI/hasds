import { motion } from "framer-motion";
import {
  HiOutlineUser,
  HiOutlineBuildingOffice,
  HiOutlineCog6Tooth,
} from "react-icons/hi2";

const portals = [
  {
    icon: <HiOutlineUser className="w-7 h-7" />,
    title: "بوابة المستفيد",
    desc: "تتيح للمواطنين فحص حالة الأولوية الخاصة بملفاتهم والوصول الآمن لرمز الاستلام الرقمي الفريد الخاص بالعائلة.",
    link: "تسجيل دخول المستفيدين",
    href: "/beneficiary/portal",
    linear: "from-emerald-400 to-teal-500",
    iconColorRest: "text-emerald-500",
    iconBgRest: "bg-emerald-50",
    btnStyles:
      "group-hover:bg-white group-hover:text-emerald-600 group-hover:border-transparent",
  },
  {
    icon: <HiOutlineBuildingOffice className="w-7 h-7" />,
    title: "بوابة الشركاء الإغاثي",
    desc: "واجهة مخصصة للبلديات والمؤسسات والجمعيات لإدخال الحالات، إدارة شحنات المستودعات، وتفعيل ماسح الـ QR للتحقق الميداني.",
    link: "تسجيل دخول المؤسسات",
    href: "/organization/login",
    secondaryHref: "/organization/register",
    secondaryLink: "طلب اعتماد مؤسسة جديدة",
    linear: "from-blue-400 to-cyan-500",
    iconColorRest: "text-blue-500",
    iconBgRest: "bg-blue-50",
    btnStyles:
      "group-hover:bg-white group-hover:text-blue-600 group-hover:border-transparent",
  },
  {
    icon: <HiOutlineCog6Tooth className="w-7 h-7" />,
    title: "بوابة الإدارة المركزية",
    desc: "لوحة تحكم عليا لمراقبة مؤشرات الأداء الحي، وإدارة حسابات الشركاء، وضبط معايير خوارزمية الترتيب والتقييم الذكي.",
    link: "تسجيل دخول لوحة التحكم",
    href: "/admin/login",
    linear: "from-purple-400 to-indigo-500",
    iconColorRest: "text-purple-500",
    iconBgRest: "bg-purple-50",
    btnStyles:
      "group-hover:bg-white group-hover:text-purple-600 group-hover:border-transparent",
  },
];

const globalTransition = { duration: 0.5, ease: "easeInOut" };

const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.1 } },
};

const cardEntranceVariants = {
  hidden: { opacity: 0, y: 30 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: "easeOut" } },
};

const cardHoverVariants = {
  rest: { y: 0, shadow: "0 1px 3px 0 rgb(0 0 0 / 0.1)" },
  hover: { y: -8 },
};

const titleVariants = {
  rest: { color: "rgb(3 7 18)" },
  hover: { color: "rgb(255 255 255)" },
};

const descVariants = {
  rest: { color: "rgb(107 114 128)" },
  hover: { color: "rgba(255, 255, 255, 0.85)" },
};

export default function PortalCards() {
  return (
    <section id="portals" className="py-10 bg-gray-50/50">
      <div className="container mx-auto px-6 max-w-7xl">
        <motion.div
          className="grid grid-cols-1 md:grid-cols-3 gap-8 w-full"
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
        >
          {portals.map((portal, index) => (
            <motion.div
              key={index}
              variants={cardEntranceVariants}
              className="h-full flex"
            >
              <motion.div
                initial="rest"
                whileHover="hover"
                variants={cardHoverVariants}
                transition={globalTransition}
                className="bg-white p-8 rounded-2xl border border-gray-100 flex flex-col items-center text-center group w-full relative overflow-hidden select-none shadow-sm hover:shadow-xl"
              >
                <div
                  className={`absolute inset-0 bg-linear-to-r ${portal.linear} opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out -z-10`}
                ></div>

                <div
                  className={`absolute top-0 left-0 right-0 h-1.5 bg-linear-to-r ${portal.linear} opacity-100 group-hover:opacity-0 transition-opacity duration-500 ease-in-out z-10`}
                ></div>

                <div
                  className={`w-14 h-14 rounded-xl flex items-center justify-center mb-6 shadow-sm transition-all duration-500 ease-in-out z-10 
                  ${portal.iconBgRest} ${portal.iconColorRest} group-hover:bg-white/20 group-hover:text-white group-hover:scale-110`}
                >
                  {portal.icon}
                </div>

                <motion.h3
                  variants={titleVariants}
                  transition={globalTransition}
                  className="font-black text-xl mb-4 tracking-tight z-10"
                >
                  {portal.title}
                </motion.h3>

                <motion.p
                  variants={descVariants}
                  transition={globalTransition}
                  className="text-sm leading-relaxed mb-8 text-gray-500 font-medium px-2 grow z-10"
                >
                  {portal.desc}
                </motion.p>

                <a
                  href={portal.href}
                  className={`block w-full text-sm font-bold py-3.5 rounded-xl border text-center shadow-sm z-10 transform transition-all duration-500 ease-in-out
                  bg-gray-50 text-gray-600 border-gray-100 cursor-pointer
                  active:scale-95 group-hover:active:bg-white/90 
                  ${portal.btnStyles}`}
                >
                  {portal.link}
                </a>
                {portal.secondaryHref ? (
                  <a
                    href={portal.secondaryHref}
                    className="mt-3 text-sm font-bold text-blue-600 hover:text-blue-700 z-10"
                  >
                    {portal.secondaryLink}
                  </a>
                ) : null}
              </motion.div>
            </motion.div>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
