export default {
  navbar: {
    home: "Home",
    portals: "Smart Portals",
    solutions: "Field Solutions",
    matrix: "Criteria Matrix",
    metrics: "Performance & Tech",
    switchTo: "العربية",
  },
  hero: {
    description:
      "An intelligent, integrated web platform for managing and distributing humanitarian relief aid in the Gaza Strip, automating processes with artificial intelligence to combat waste, prevent duplicate distribution, and ensure the highest standards of integrity and field-level fairness.",
  },
  portals: {
    beneficiary: {
      title: "Beneficiary Portal",
      desc: "Lets citizens check their case's priority status and securely access their family's unique digital pickup code.",
      link: "Beneficiary Login",
    },
    organization: {
      title: "Relief Partners Portal",
      desc: "A dedicated interface for municipalities, organizations, and associations to register cases, manage warehouse shipments, and activate the QR scanner for field verification.",
      link: "Organization Login",
      secondaryLink: "Request New Organization Approval",
    },
    admin: {
      title: "Central Management Portal",
      desc: "A top-level dashboard for monitoring live performance indicators, managing partner accounts, and tuning the smart ranking and evaluation algorithm's criteria.",
      link: "Dashboard Login",
    },
  },
  solutions: {
    heading: "Smart Processing & Field Gap Solutions",
    subheading:
      "The system was built to address the critical problems facing traditional relief work in the Gaza Strip",
    items: {
      duplication: {
        title: "Duplication Prevention",
        desc: "Relying on a strict structured database that prevents identical aid from being randomly issued to the same beneficiary more than once, reducing waste and improving distribution efficiency.",
      },
      prioritization: {
        title: "Automated Priority Ranking",
        desc: "Integrating Python machine-learning models to analyze and classify family case severity based on an advanced, multi-dimensional criteria matrix.",
      },
      emergency: {
        title: "Emergency Response",
        desc: "Supporting rapid-response plans for sudden field developments, and accelerating the processing path for urgent cases when needed.",
      },
    },
  },
  matrix: {
    heading: "Machine Learning Model Criteria Matrix",
    columns: {
      variable: "Input Variable for the Smart Model",
      mechanism: "Effect Mechanism on Priority Eligibility",
    },
    rows: [
      {
        variable: "Family size and number of children/elderly",
        mechanism: "Direct correlation with degree of need",
      },
      {
        variable: "Actual monthly income level",
        mechanism: "Inverse correlation to determine poverty level",
      },
      {
        variable: "Displacement duration and current housing type",
        mechanism: "Maximum-priority criterion for shelters and tents",
      },
      {
        variable: "Health status and presence of chronic conditions or disability",
        mechanism: "Additional cumulative weight that automatically raises priority",
      },
    ],
  },
  metrics: {
    heading: "Target Performance Goals & Technologies Used",
    subheading: "The project's engineering environment, and the performance-indicator design targets (not yet field-measured)",
    items: {
      accuracy: { title: "Target Smart Ranking Accuracy" },
      reduction: { title: "Target Duplication Reduction Rate" },
      users: { title: "Target Concurrent Users" },
    },
    stackHeading: "System Software Architecture",
  },
  footer: {
    projectTitle: "Graduation Project 2",
    college: "Faculty of Software Engineering & Artificial Intelligence • Palestine University",
    supervisedBy: "Supervised by Dr.:",
    teamHeading: "Development Team (Students):",
  },
};
