import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import ar from "./locales/ar";
import en from "./locales/en";

const STORAGE_KEY = "hasds_locale";

function resolveInitialLocale() {
  const stored = window.localStorage.getItem(STORAGE_KEY);
  if (stored === "ar" || stored === "en") {
    return stored;
  }

  const serverLocale = document.getElementById("root")?.dataset?.locale;
  if (serverLocale === "ar" || serverLocale === "en") {
    return serverLocale;
  }

  return "ar";
}

i18n.use(initReactI18next).init({
  resources: {
    ar: { translation: ar },
    en: { translation: en },
  },
  lng: resolveInitialLocale(),
  fallbackLng: "en",
  interpolation: { escapeValue: false },
});

i18n.on("languageChanged", (lng) => {
  window.localStorage.setItem(STORAGE_KEY, lng);
  document.documentElement.lang = lng;
  document.documentElement.dir = i18n.dir(lng);

  fetch(`/lang/${lng}`, { headers: { "X-Requested-With": "XMLHttpRequest" } }).catch(() => {});
});

export default i18n;
