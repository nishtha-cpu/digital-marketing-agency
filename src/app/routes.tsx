import { createBrowserRouter } from "react-router";
import { DashboardLayout } from "./components/DashboardLayout";
import { DashboardPage } from "./pages/DashboardPage";
import { ProgramsPage } from "./pages/ProgramsPage";
import { BlogsPage } from "./pages/BlogsPage";
import { EventsPage } from "./pages/EventsPage";
import { TestimonialsPage } from "./pages/TestimonialsPage";
import { TeamPage } from "./pages/TeamPage";
import { VolunteersPage } from "./pages/VolunteersPage";
import { DonationsPage } from "./pages/DonationsPage";
import { ContactPage } from "./pages/ContactPage";
import { NewsletterPage } from "./pages/NewsletterPage";
import { SettingsPage } from "./pages/SettingsPage";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: DashboardLayout,
    children: [
      { index: true, Component: DashboardPage },
      { path: "programs", Component: ProgramsPage },
      { path: "blogs", Component: BlogsPage },
      { path: "events", Component: EventsPage },
      { path: "testimonials", Component: TestimonialsPage },
      { path: "team", Component: TeamPage },
      { path: "volunteers", Component: VolunteersPage },
      { path: "donations", Component: DonationsPage },
      { path: "contacts", Component: ContactPage },
      { path: "newsletter", Component: NewsletterPage },
      { path: "settings", Component: SettingsPage },
    ],
  },
]);
