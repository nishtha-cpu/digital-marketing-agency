import { useState, useEffect } from "react";
import { useNavigate } from "react-router";
import {
  Heart,
  Calendar,
  FileText,
  Users,
  Trophy,
  Mail,
  ArrowUp,
  TrendingUp,
} from "lucide-react";
import {
  PieChart,
  Pie,
  Cell,
  Tooltip,
  ResponsiveContainer,
} from "recharts";
import { storage, Donation, Volunteer, ContactEnquiry, Subscriber } from "../utils/storage";

const COLORS = ["#2E7D32", "#4CAF50", "#81C784", "#A5D6A7"];

export function DashboardPage() {
  const navigate = useNavigate();
  const [donations, setDonations] = useState<Donation[]>([]);
  const [volunteers, setVolunteers] = useState<Volunteer[]>([]);
  const [contacts, setContacts] = useState<ContactEnquiry[]>([]);
  const [subscribers, setSubscribers] = useState<Subscriber[]>([]);

  useEffect(() => {
    setDonations(storage.getDonations());
    setVolunteers(storage.getVolunteers());
    setContacts(storage.getContacts());
    setSubscribers(storage.getSubscribers());
  }, []);

  const totalDonationsAmount = donations.reduce((sum, d) => sum + d.amount, 0);
  const totalSubscribers = subscribers.length;
  const totalVolunteers = volunteers.length;

  const stats = [
    {
      label: "Total Donations",
      value: `₹${totalDonationsAmount.toLocaleString("en-IN")}`,
      change: donations.length > 0 ? `+${donations.length} items` : "0 items",
      trend: "up",
      icon: Heart,
      color: "#EF4444",
    },
    {
      label: "Programs Running",
      value: "12",
      change: "Active",
      trend: "up",
      icon: Calendar,
      color: "#8B5CF6",
    },
    {
      label: "Newsletter Subscribers",
      value: totalSubscribers.toString(),
      change: `${subscribers.filter(s => s.status === "Active").length} active`,
      trend: "up",
      icon: FileText,
      color: "#10B981",
    },
    {
      label: "Total Members",
      value: (totalSubscribers + totalVolunteers).toString(),
      change: "Subscribers + Volunteers",
      trend: "up",
      icon: Users,
      color: "#6366F1",
    },
  ];

  // Dynamic program distribution based on volunteer skills
  const programMap: { [key: string]: number } = {};
  volunteers.forEach(v => {
    const skillsList = v.skills.split(",").map(s => s.trim());
    skillsList.forEach(skill => {
      if (skill) {
        programMap[skill] = (programMap[skill] || 0) + 1;
      }
    });
  });

  const programData = Object.keys(programMap).map(name => ({
    name,
    value: programMap[name],
  })).slice(0, 4);

  // Fallback program data if no volunteers are registered yet
  const displayProgramData = programData.length > 0 ? programData : [
    { name: "Education", value: 1 },
    { name: "Research", value: 1 },
    { name: "Mentorship", value: 1 },
    { name: "Community", value: 1 },
  ];

  // Build dynamic activities from real data
  const activities: { title: string; description: string; time: string; type: string }[] = [];

  donations.forEach(d => {
    activities.push({
      title: "Donation Received",
      description: `₹${d.amount.toLocaleString("en-IN")} from ${d.donor}`,
      time: d.date,
      type: "donation",
    });
  });

  volunteers.forEach(v => {
    activities.push({
      title: "Volunteer Registered",
      description: `${v.name} joined (${v.skills})`,
      time: v.joined,
      type: "volunteer",
    });
  });

  contacts.forEach(c => {
    activities.push({
      title: "Contact Enquiry Received",
      description: `Enquiry from ${c.name}: "${c.enqueri.substring(0, 35)}${c.enqueri.length > 35 ? "..." : ""}"`,
      time: c.date,
      type: "contact",
    });
  });

  // Sort activities by date/time (we can use local comparison, latest first)
  activities.sort((a, b) => b.time.localeCompare(a.time));
  const recentActivities = activities.slice(0, 5);

  const quickActions = [
    { label: "Manage Donations", icon: Heart, color: "#EF4444", path: "/donations" },
    { label: "Add Volunteer", icon: Trophy, color: "#F59E0B", path: "/volunteers" },
    { label: "Newsletter Subscribers", icon: FileText, color: "#10B981", path: "/newsletter" },
    { label: "Settings", icon: SettingsIcon, color: "#6366F1", path: "/settings" },
  ];

  // A tiny local component wrapper or icon fallback
  function SettingsIcon(props: any) {
    return <FileText {...props} />;
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-semibold text-foreground">
          Dashboard Overview
        </h1>
        <p className="text-muted-foreground mt-1">
          Welcome back! Here's what's happening with your organization.
        </p>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, index) => {
          const Icon = stat.icon;
          return (
            <div
              key={index}
              className="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-md transition-shadow"
            >
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <p className="text-sm text-muted-foreground">{stat.label}</p>
                  <h3 className="text-2xl font-semibold mt-2 text-foreground">
                    {stat.value}
                  </h3>
                  <div className="flex items-center gap-1 mt-2">
                    <ArrowUp className="w-4 h-4 text-[#22C55E]" />
                    <span className="text-sm text-[#22C55E]">{stat.change}</span>
                  </div>
                </div>
                <div
                  className="w-12 h-12 rounded-lg flex items-center justify-center"
                  style={{ backgroundColor: `${stat.color}15` }}
                >
                  <Icon className="w-6 h-6" style={{ color: stat.color }} />
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {/* Program Distribution & Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Program Distribution */}
        <div className="bg-card rounded-xl p-6 border border-border shadow-sm">
          <h3 className="font-semibold text-foreground mb-4">
            Program Distribution
          </h3>
          <div className="h-[200px] w-full flex items-center justify-center">
            <ResponsiveContainer width="100%" height="100%">
              <PieChart>
                <Pie
                  data={displayProgramData}
                  cx="50%"
                  cy="50%"
                  innerRadius={50}
                  outerRadius={75}
                  paddingAngle={5}
                  dataKey="value"
                >
                  {displayProgramData.map((entry, index) => (
                    <Cell
                      key={`cell-${index}`}
                      fill={COLORS[index % COLORS.length]}
                    />
                  ))}
                </Pie>
                <Tooltip />
              </PieChart>
            </ResponsiveContainer>
          </div>
          <div className="mt-4 space-y-2 max-h-[140px] overflow-y-auto pr-1">
            {displayProgramData.map((item, index) => (
              <div key={index} className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <div
                    className="w-3 h-3 rounded-full"
                    style={{ backgroundColor: COLORS[index % COLORS.length] }}
                  ></div>
                  <span className="text-xs text-muted-foreground truncate max-w-[150px]">
                    {item.name}
                  </span>
                </div>
                <span className="text-xs font-medium">{item.value} volunteers</span>
              </div>
            ))}
          </div>
        </div>

        {/* Recent Activity */}
        <div className="lg:col-span-2 bg-card rounded-xl p-6 border border-border shadow-sm">
          <h3 className="font-semibold text-foreground mb-4">
            Recent Activity
          </h3>
          <div className="space-y-4">
            {recentActivities.length === 0 ? (
              <div className="text-center py-12 text-sm text-muted-foreground">
                No recent activity yet. Added donations, volunteers, and enquiries will show up here.
              </div>
            ) : (
              recentActivities.map((activity, index) => (
                <div
                  key={index}
                  className="flex items-start gap-4 pb-4 border-b border-border last:border-0 last:pb-0"
                >
                  <div className="w-10 h-10 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                    {activity.type === "donation" && (
                      <Heart className="w-5 h-5 text-[#EF4444]" />
                    )}
                    {activity.type === "volunteer" && (
                      <Trophy className="w-5 h-5 text-[#F59E0B]" />
                    )}
                    {activity.type === "contact" && (
                      <Mail className="w-5 h-5 text-[#3B82F6]" />
                    )}
                  </div>
                  <div className="flex-1 min-w-0">
                    <h4 className="font-medium text-foreground text-sm truncate">
                      {activity.title}
                    </h4>
                    <p className="text-xs text-muted-foreground truncate">
                      {activity.description}
                    </p>
                  </div>
                  <span className="text-xs text-muted-foreground flex-shrink-0">
                    {activity.time}
                  </span>
                </div>
              ))
            )}
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="bg-card rounded-xl p-6 border border-border shadow-sm">
        <h3 className="font-semibold text-foreground mb-4">Quick Actions</h3>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {quickActions.map((action, index) => {
            const Icon = action.icon;
            return (
              <button
                key={index}
                onClick={() => navigate(action.path)}
                className="flex flex-col items-center gap-3 p-6 rounded-xl border border-border hover:border-primary hover:shadow-md transition-all bg-accent/50 hover:bg-accent cursor-pointer"
              >
                <div
                  className="w-12 h-12 rounded-lg flex items-center justify-center"
                  style={{ backgroundColor: `${action.color}15` }}
                >
                  <Icon className="w-6 h-6" style={{ color: action.color }} />
                </div>
                <span className="font-medium text-foreground text-sm text-center">
                  {action.label}
                </span>
              </button>
            );
          })}
        </div>
      </div>
    </div>
  );
}
