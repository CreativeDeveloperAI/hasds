import { useTranslation } from "react-i18next";
import Navbar from "./components/Navbar";
import Hero from "./components/Hero";
import PortalCards from "./components/PortalCards";
import Solutions from "./components/Solutions";
import CriteriaTable from "./components/CriteriaTable";
import MetricsAndTech from "./components/MetricsAndTech";
import Footer from "./components/Footer";

function App() {
  const { i18n } = useTranslation();

  return (
    <div
      className="bg-white min-h-screen text-gray-900 font-sans selection:bg-teal-500 selection:text-white"
      dir={i18n.dir()}
    >
      <Navbar />
      <main>
        <Hero />
        <PortalCards />
        <Solutions />
        <CriteriaTable />
        <MetricsAndTech />
      </main>
      <Footer />
    </div>
  );
}

export default App;
