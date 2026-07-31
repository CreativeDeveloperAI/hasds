import { motion } from "framer-motion";
import { useTranslation } from "react-i18next";

const metrics = [
  { key: "accuracy", value: "90 %" },
  { key: "reduction", value: "95 %" },
  { key: "users", value: "1000+" },
];

const rowOneStack = [
  { label: "Frontend: Filament/Livewire + React.js 19.x" },
  { label: "Backend: Laravel 13.x (PHP 8.4)" },
];

const rowTwoStack = [
  { label: "UI/UX: Figma" },
  { label: "Database: MySQL 8.0" },
  { label: "AI Pipeline: Python (FastAPI & Scikit-learn)" },
];

const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.1 } },
};

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.6, ease: "easeOut" } },
};

export default function TechMetrics() {
  const { t, i18n } = useTranslation();

  return (
    <section
      id="metrics"
      className="py-20 bg-white flex items-center justify-center font-sans"
      dir={i18n.dir()}
    >
      <div className="container mx-auto px-6 max-w-5xl text-center">
        <div className="mb-6">
          <h2 className="text-2xl md:text-3xl font-black text-gray-950 tracking-tight mb-3">
            {t("metrics.heading")}
          </h2>
          <p className="text-gray-400 font-bold text-base md:text-lg mb-12">
            {t("metrics.subheading")}
          </p>
        </div>
        <motion.div
          className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 max-w-4xl mx-auto"
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
        >
          {metrics.map((item, index) => (
            <motion.div
              key={index}
              variants={itemVariants}
              className="border-2 border-dashed border-gray-400/80 rounded-2xl p-6 flex flex-col items-center justify-center bg-white"
            >
              <h3
                style={{ color: "#346B68" }}
                className="text-lg md:text-xl font-black mb-3"
              >
                {t(`metrics.items.${item.key}.title`)}
              </h3>
              <p
                dir="ltr"
                className="text-gray-950 text-xl md:text-2xl font-normal tracking-tight"
              >
                {item.value}
              </p>
            </motion.div>
          ))}
        </motion.div>

        <div className="mb-8">
          <h3 className="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">
            {t("metrics.stackHeading")}
          </h3>
        </div>
        <div
          className="flex flex-col gap-4 max-w-4xl mx-auto items-center"
          dir="ltr"
        >
          <motion.div
            className="flex flex-row justify-center items-center gap-4 flex-wrap"
            variants={containerVariants}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, margin: "-100px" }}
          >
            {rowOneStack.map((tech, index) => (
              <motion.div
                key={index}
                variants={itemVariants}
                style={{
                  backgroundColor: "rgba(184, 242, 242, 0.25)",
                  borderColor: "rgba(52, 107, 104, 0.15)",
                  color: "#346B68",
                }}
                className="px-6 py-3 rounded-xl border font-bold text-sm md:text-base tracking-wide shadow-sm whitespace-nowrap"
              >
                {tech.label}
              </motion.div>
            ))}
          </motion.div>
          <motion.div
            className="flex flex-row justify-center items-center gap-4 flex-wrap"
            variants={containerVariants}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, margin: "-100px" }}
          >
            {rowTwoStack.map((tech, index) => (
              <motion.div
                key={index}
                variants={itemVariants}
                style={{
                  backgroundColor: "rgba(184, 242, 242, 0.25)",
                  borderColor: "rgba(52, 107, 104, 0.15)",
                  color: "#346B68",
                }}
                className="px-6 py-3 rounded-xl border font-bold text-sm md:text-base tracking-wide shadow-sm whitespace-nowrap"
              >
                {tech.label}
              </motion.div>
            ))}
          </motion.div>
        </div>
      </div>
    </section>
  );
}
