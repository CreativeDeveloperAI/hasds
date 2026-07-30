import { motion } from "framer-motion";
import { useTranslation } from "react-i18next";

const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.08 } },
};

const rowVariants = {
  hidden: { opacity: 0, y: 15 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: "easeOut" } },
};

export default function CriteriaMatrix() {
  const { t, i18n } = useTranslation();
  const matrixData = t("matrix.rows", { returnObjects: true });

  return (
    <section
      id="matrix"
      className="py-20 bg-gray-50/30 flex items-center justify-center min-h-screen font-sans"
      dir={i18n.dir()}
    >
      <div className="container mx-auto px-6 max-w-5xl">
        <div className="bg-white/60 backdrop-blur-md p-8 md:p-12 rounded-2xl border border-gray-100 shadow-sm w-full">
          <div className="text-center mb-12">
            <h2 className="text-2xl md:text-4xl font-black text-gray-950 tracking-tight">
              {t("matrix.heading")}
            </h2>
          </div>
          <div className="overflow-hidden rounded-2xl border border-gray-100/90 bg-white shadow-md">
            <table
              className={`w-full border-collapse ${i18n.dir() === "rtl" ? "text-right" : "text-left"}`}
            >
              <thead>
                <tr
                  style={{ backgroundColor: "rgba(184, 242, 242, 0.4)" }}
                  className="border-b border-gray-100"
                >
                  <th
                    style={{ color: "#346B68" }}
                    className="py-5 px-6 md:px-8 text-sm md:text-base tracking-wide w-1/2"
                  >
                    {t("matrix.columns.variable")}
                  </th>
                  <th
                    style={{ color: "#346B68" }}
                    className="py-5 px-6 md:px-8 text-sm md:text-base tracking-wide w-1/2"
                  >
                    {t("matrix.columns.mechanism")}
                  </th>
                </tr>
              </thead>
              <motion.tbody
                variants={containerVariants}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true, margin: "-100px" }}
              >
                {matrixData.map((row, index) => (
                  <motion.tr
                    key={index}
                    variants={rowVariants}
                    className="border-b border-gray-100 last:border-b-0 bg-white"
                  >
                    <td className="py-6 px-6 md:px-8 text-gray-950 font-bold text-sm md:text-base leading-relaxed">
                      {row.variable}
                    </td>
                    <td className="py-6 px-6 md:px-8 text-gray-950 font-bold text-sm md:text-base leading-relaxed">
                      {row.mechanism}
                    </td>
                  </motion.tr>
                ))}
              </motion.tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  );
}
