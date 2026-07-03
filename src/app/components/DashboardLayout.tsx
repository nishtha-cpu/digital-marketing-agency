import { useState } from "react";
import { Outlet, Link, useLocation } from "react-router";
import {
  LayoutDashboard,
  Home,
  BookOpen,
  Calendar,
  MessageSquare,
  UsersRound,
  Heart,
  Mail,
  Settings,
  Menu,
  X,
  Search,
  Bell,
  User,
  ChevronRight,
  GraduationCap,
  Briefcase,
  Trophy,
  FileText,
  LogIn,
} from "lucide-react";

const menuItems = [
  { path: "/", icon: LayoutDashboard, label: "Dashboard" },
  { path: "/programs", icon: GraduationCap, label: "Programs" },
  { path: "/blogs", icon: BookOpen, label: "Blogs" },
  { path: "/events", icon: Calendar, label: "Events" },
  { path: "/testimonials", icon: MessageSquare, label: "Testimonials" },
  { path: "/team", icon: UsersRound, label: "Team Members" },
  { path: "/volunteers", icon: Trophy, label: "Volunteers" },
  { path: "/donations", icon: Heart, label: "Donations" },
  { path: "/contacts", icon: Mail, label: "Contact Enquiries" },
  { path: "/newsletter", icon: FileText, label: "Newsletter" },
  { path: "/settings", icon: Settings, label: "Settings" },
];


export function DashboardLayout() {
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);
  const location = useLocation();

  const isActive = (path: string) => {
    if (path === "/") {
      return location.pathname === "/";
    }
    return location.pathname.startsWith(path);
  };

  return (
    <div className="min-h-screen bg-background">
      {/* Sidebar */}
      <aside
        className={`fixed left-0 top-0 z-40 h-screen bg-card border-r border-border transition-all duration-300 ${
          isSidebarOpen ? "w-64" : "w-20"
        }`}
      >
        {/* Logo */}
        <div className="h-16 flex items-center justify-between px-4 border-b border-border">
          {isSidebarOpen && (
            <div className="flex items-center gap-2">
              <div className="w-8 h-8 rounded-lg bg-[#2E7D32] flex items-center justify-center">
                <GraduationCap className="w-5 h-5 text-white" />
              </div>
              <span className="font-semibold text-foreground">PrayogBharti</span>
            </div>
          )}
          <button
            onClick={() => setIsSidebarOpen(!isSidebarOpen)}
            className="p-2 hover:bg-accent rounded-lg transition-colors"
          >
            {isSidebarOpen ? (
              <X className="w-5 h-5" />
            ) : (
              <Menu className="w-5 h-5" />
            )}
          </button>
        </div>

        {/* Menu */}
        <nav className="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
          {menuItems.map((item) => {
            const Icon = item.icon;
            const active = isActive(item.path);
            return (
              <Link
                key={item.path}
                to={item.path}
                className={`flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all ${
                  active
                    ? "bg-[#2E7D32] text-white"
                    : "text-muted-foreground hover:bg-accent hover:text-foreground"
                }`}
              >
                <Icon className="w-5 h-5 flex-shrink-0" />
                {isSidebarOpen && (
                  <span className="text-sm font-medium">{item.label}</span>
                )}
              </Link>
            );
          })}
        </nav>
      </aside>

      {/* Main Content */}
      <div
        className={`transition-all duration-300 ${
          isSidebarOpen ? "ml-64" : "ml-20"
        }`}
      >
        {/* Top Navigation */}
        <header className="h-16 bg-card border-b border-border sticky top-0 z-30">
          <div className="h-full px-6 flex items-center justify-between">
            {/* Breadcrumb */}
            <div className="flex items-center gap-2 text-sm">
              <Home className="w-4 h-4 text-muted-foreground" />
              <ChevronRight className="w-4 h-4 text-muted-foreground" />
              <span className="text-foreground font-medium">
                {menuItems.find((item) => isActive(item.path))?.label ||
                  "Dashboard"}
              </span>
            </div>

            {/* Right Section */}
            <div className="flex items-center gap-4">
              {/* Search */}
              <div className="relative">
                <Search className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
                <input
                  type="text"
                  placeholder="Search..."
                  className="pl-10 pr-4 py-2 w-64 bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm"
                />
              </div>

              {/* Notifications */}
              <button className="relative p-2 hover:bg-accent rounded-lg transition-colors">
                <Bell className="w-5 h-5" />
                <span className="absolute top-1 right-1 w-2 h-2 bg-[#EF4444] rounded-full"></span>
              </button>

              {/* Profile */}
              <button className="flex items-center gap-3 hover:bg-accent rounded-lg px-3 py-2 transition-colors">
                <div className="w-8 h-8 rounded-full bg-[#2E7D32] flex items-center justify-center">
                  <User className="w-4 h-4 text-white" />
                </div>
                <div className="text-left hidden lg:block">
                  <div className="text-sm font-medium">Admin User</div>
                  <div className="text-xs text-muted-foreground">
                    Administrator
                  </div>
                </div>
              </button>

              {/* Log In Button */}
              <button className="flex items-center gap-2 px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors text-sm font-medium">
                <LogIn className="w-4 h-4" />
                Log In
              </button>
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className="p-6">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
