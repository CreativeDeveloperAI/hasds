import Navbar from "./components/Navbar";
import Hero from "./components/Hero";
import PortalCards from "./components/PortalCards";
import Solutions from "./components/Solutions";
import CriteriaTable from "./components/CriteriaTable";
import MetricsAndTech from "./components/MetricsAndTech";
import Footer from "./components/Footer";

function App() {
  return (
    <div
      className="bg-white min-h-screen text-gray-900 font-sans selection:bg-teal-500 selection:text-white"
      dir="rtl"
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
