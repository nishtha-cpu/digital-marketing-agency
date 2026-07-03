import { Plus, Edit, Trash2, GraduationCap } from "lucide-react";

const programs = [
  {
    id: 1,
    name: "Scholarship Program 2026",
    category: "Education",
    participants: 150,
    status: "Active",
  },
  {
    id: 2,
    name: "Research Fellowship",
    category: "Research",
    participants: 45,
    status: "Active",
  },
  {
    id: 3,
    name: "Mentorship Initiative",
    category: "Mentorship",
    participants: 200,
    status: "Active",
  },
  {
    id: 4,
    name: "Community Development",
    category: "Community",
    participants: 350,
    status: "Active",
  },
];

export function ProgramsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">Programs</h1>
          <p className="text-muted-foreground mt-1">
            Manage your organization's programs and initiatives
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors">
          <Plus className="w-5 h-5" />
          Add Program
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Programs</div>
          <div className="text-2xl font-semibold mt-1">12</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Active</div>
          <div className="text-2xl font-semibold mt-1">10</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Participants</div>
          <div className="text-2xl font-semibold mt-1">745</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Completed</div>
          <div className="text-2xl font-semibold mt-1">28</div>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {programs.map((program) => (
          <div
            key={program.id}
            className="bg-card rounded-xl p-6 border border-border hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between mb-4">
              <div className="flex items-start gap-3">
                <div className="w-12 h-12 rounded-lg bg-[#E8F5E9] flex items-center justify-center">
                  <GraduationCap className="w-6 h-6 text-[#2E7D32]" />
                </div>
                <div>
                  <h3 className="font-semibold text-foreground">
                    {program.name}
                  </h3>
                  <p className="text-sm text-muted-foreground mt-1">
                    {program.category}
                  </p>
                </div>
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
            <div className="flex items-center justify-between">
              <div>
                <div className="text-sm text-muted-foreground">
                  Participants
                </div>
                <div className="text-xl font-semibold mt-1">
                  {program.participants}
                </div>
              </div>
              <span className="px-3 py-1 text-sm rounded-full bg-[#D1FAE5] text-[#059669]">
                {program.status}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
