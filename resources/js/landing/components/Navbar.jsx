import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { useTranslation } from "react-i18next";
import { HiBars3BottomRight, HiXMark, HiLanguage } from "react-icons/hi2";
import logoImg from "../assets/HASDS logo.svg";

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);
  const { t, i18n } = useTranslation();

  const navLinks = [
    { name: t("navbar.home"), href: "#hero" },
    { name: t("navbar.portals"), href: "#portals" },
    { name: t("navbar.solutions"), href: "#solutions" },
    { name: t("navbar.matrix"), href: "#matrix" },
    { name: t("navbar.metrics"), href: "#metrics" },
  ];

  const toggleLanguage = () => {
    i18n.changeLanguage(i18n.language === "ar" ? "en" : "ar");
  };

  return (
    <header className="bg-white/75 backdrop-blur-xl shadow-sm fixed top-0 left-0 right-0 z-50 border-b border-gray-100/80 transition-all">
      <nav className="container mx-auto px-6 py-4 flex items-center justify-between">
        <div className="flex items-center gap-3 group cursor-pointer" dir="ltr">
          <span className="font-black text-xl bg-linear-to-r from-teal-950 to-teal-700 bg-clip-text text-transparent">
            HASDS
          </span>
          <img
            src={logoImg}
            alt="HASDS Logo"
            className="w-10 h-10 object-contain group-hover:scale-105 transition-transform select-none"
          />
        </div>
        <div className="hidden lg:flex items-center gap-8 text-sm font-bold">
          {navLinks.map((link, idx) => (
            <a
              key={idx}
              href={link.href}
              className="text-gray-600 hover:text-teal-600 relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-teal-500 hover:after:w-full after:transition-all after:duration-300"
            >
              {link.name}
            </a>
          ))}
        </div>

        <div className="hidden lg:block">
          <button
            onClick={toggleLanguage}
            className="flex items-center gap-2 bg-linear-to-r from-teal-600 to-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-extrabold shadow-lg shadow-teal-600/20 hover:shadow-teal-600/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
          >
            <HiLanguage className="w-5 h-5" />
            {t("navbar.switchTo")}
          </button>
        </div>

        <button
          onClick={() => setIsOpen(!isOpen)}
          className="lg:hidden p-2 text-gray-700 hover:text-teal-600 transition-colors"
        >
          {isOpen ? (
            <HiXMark className="w-6 h-6" />
          ) : (
            <HiBars3BottomRight className="w-6 h-6" />
          )}
        </button>
      </nav>

      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: "auto" }}
            exit={{ opacity: 0, height: 0 }}
            className="lg:hidden bg-white border-t border-gray-100 overflow-hidden shadow-inner"
          >
            <div className="flex flex-col p-6 gap-4 font-bold text-sm">
              {navLinks.map((link, idx) => (
                <a
                  key={idx}
                  href={link.href}
                  onClick={() => setIsOpen(false)}
                  className="text-gray-600 hover:text-teal-600 py-2 border-b border-gray-50 last:border-0"
                >
                  {link.name}
                </a>
              ))}
              <button
                onClick={toggleLanguage}
                className="flex items-center justify-center gap-2 bg-linear-to-r from-teal-600 to-emerald-600 text-white w-full py-3 rounded-xl font-extrabold mt-2 text-center shadow-md"
              >
                <HiLanguage className="w-5 h-5" />
                {t("navbar.switchTo")}
              </button>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}
