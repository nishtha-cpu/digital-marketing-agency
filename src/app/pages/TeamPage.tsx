import { Plus, Mail, Phone, Edit, Trash2 } from "lucide-react";

const teamMembers = [
  {
    id: 1,
    name: "Dr. Rajesh Kumar",
    role: "Founder & Director",
    department: "Leadership",
    email: "rajesh@prayogbharti.org",
    phone: "+91 98765 43210",
  },
  {
    id: 2,
    name: "Priya Sharma",
    role: "Program Manager",
    department: "Programs",
    email: "priya@prayogbharti.org",
    phone: "+91 98765 43211",
  },
  {
    id: 3,
    name: "Amit Patel",
    role: "Research Head",
    department: "Research",
    email: "amit@prayogbharti.org",
    phone: "+91 98765 43212",
  },
];

export function TeamPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">
            Team Members
          </h1>
          <p className="text-muted-foreground mt-1">
            Manage your organization's team and staff
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors">
          <Plus className="w-5 h-5" />
          Add Team Member
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Members</div>
          <div className="text-2xl font-semibold mt-1">24</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Departments</div>
          <div className="text-2xl font-semibold mt-1">6</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Leadership</div>
          <div className="text-2xl font-semibold mt-1">5</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Staff</div>
          <div className="text-2xl font-semibold mt-1">19</div>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {teamMembers.map((member) => (
          <div
            key={member.id}
            className="bg-card rounded-xl p-6 border border-border hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between mb-4">
              <div className="w-16 h-16 rounded-full bg-[#2E7D32] flex items-center justify-center text-white text-xl font-semibold">
                {member.name
                  .split(" ")
                  .map((n) => n[0])
                  .join("")}
              </div>
              <div className="flex gap-2">
                <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                  <Edit className="w-4 h-4 text-muted-foreground" />
                </button>
                <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                  <Trash2 className="w-4 h-4 text-[#EF4444]" />
                </button>
              </div>
            </div>
            <h3 className="font-semibold text-foreground text-lg">
              {member.name}
            </h3>
            <p className="text-sm text-muted-foreground mt-1">{member.role}</p>
            <span className="inline-block px-2 py-1 text-xs rounded-full bg-[#E8F5E9] text-[#2E7D32] mt-2">
              {member.department}
            </span>
            <div className="mt-4 space-y-2">
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <Mail className="w-4 h-4" />
                <span>{member.email}</span>
              </div>
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <Phone className="w-4 h-4" />
                <span>{member.phone}</span>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
