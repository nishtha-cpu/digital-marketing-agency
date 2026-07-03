import { useState, useEffect, useRef } from "react";
import { Search, MoreVertical, Check, Clock, X } from "lucide-react";
import { storage, Volunteer } from "../utils/storage";

export function VolunteersPage() {
  const [volunteers, setVolunteers] = useState<Volunteer[]>([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [openMenuId, setOpenMenuId] = useState<string | null>(null);
  const menuRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    setVolunteers(storage.getVolunteers());
  }, []);

  // Close dropdown on outside click
  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (menuRef.current && !menuRef.current.contains(e.target as Node)) {
        setOpenMenuId(null);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const handleStatusChange = (id: string, status: "Pending" | "Approved" | "Rejected") => {
    const updated = volunteers.map((v) =>
      v.id === id ? { ...v, status } : v
    );
    setVolunteers(updated);
    storage.saveVolunteers(updated);
    setOpenMenuId(null);
  };

  const filteredVolunteers = volunteers.filter(
    (v) =>
      v.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      v.skills.toLowerCase().includes(searchQuery.toLowerCase()) ||
      v.email.toLowerCase().includes(searchQuery.toLowerCase()) ||
      v.aadhar.includes(searchQuery)
  );

  // Stats
  const totalVolunteers = volunteers.length;
  const uniqueSkills = new Set(
    volunteers.flatMap((v) => v.skills.split(",").map((s) => s.trim().toLowerCase()))
  ).size;

  const statusColor = (status: Volunteer["status"]) => {
    if (status === "Approved") return "bg-[#D1FAE5] text-[#059669]";
    if (status === "Rejected") return "bg-[#FEE2E2] text-[#DC2626]";
    return "bg-[#FEF3C7] text-[#D97706]";
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground font-display">
            Volunteers
          </h1>
          <p className="text-muted-foreground mt-1">
            Manage your organization's volunteers and contributors
          </p>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Volunteers</div>
          <div className="text-2xl font-semibold mt-1">{totalVolunteers}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Skills Represented</div>
          <div className="text-2xl font-semibold mt-1">{uniqueSkills}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Latest Signup</div>
          <div className="text-lg font-medium mt-1">
            {volunteers.length > 0 ? volunteers[volunteers.length - 1].name : "No volunteers yet"}
          </div>
        </div>
      </div>

      {/* Search & Actions Panel */}
      <div className="bg-card rounded-xl p-4 border border-border">
        <div className="relative">
          <Search className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
          <input
            type="text"
            placeholder="Search volunteers by name, skills, email, aadhar..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="pl-10 pr-4 py-2 w-full md:w-80 bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm text-foreground"
          />
        </div>
      </div>

      {/* Table Card */}
      <div className="bg-card rounded-xl border border-border overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-accent border-b border-border">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Name
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Skills
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Email
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Aadhar No.
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Date of Joining
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Status
                </th>
                <th className="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">
                  Action
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border">
              {filteredVolunteers.length === 0 ? (
                <tr>
                  <td colSpan={7} className="px-6 py-12 text-center text-muted-foreground text-sm">
                    No volunteers found.
                  </td>
                </tr>
              ) : (
                filteredVolunteers.map((volunteer) => (
                  <tr key={volunteer.id} className="hover:bg-accent/50 transition-colors">
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <div className="w-9 h-9 rounded-full bg-[#2E7D32] flex items-center justify-center text-white font-semibold text-sm">
                          {volunteer.name.charAt(0)}
                        </div>
                        <span className="font-medium text-foreground">{volunteer.name}</span>
                      </div>
                    </td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{volunteer.skills}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{volunteer.email}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{volunteer.aadhar}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{volunteer.joined}</td>
                    <td className="px-6 py-4">
                      <span className={`px-2.5 py-1 text-xs rounded-full font-medium ${statusColor(volunteer.status ?? "Pending")}`}>
                        {volunteer.status ?? "Pending"}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="relative inline-block" ref={openMenuId === volunteer.id ? menuRef : undefined}>
                        <button
                          onClick={() => setOpenMenuId(openMenuId === volunteer.id ? null : volunteer.id)}
                          className="p-1.5 hover:bg-accent rounded-lg text-muted-foreground transition-colors cursor-pointer"
                          title="Actions"
                        >
                          <MoreVertical className="w-4 h-4" />
                        </button>
                        {openMenuId === volunteer.id && (
                          <div className="absolute right-0 mt-1 w-40 bg-card border border-border rounded-xl shadow-lg z-50 overflow-hidden">
                            <button
                              onClick={() => handleStatusChange(volunteer.id, "Pending")}
                              className="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-[#D97706] hover:bg-[#FEF3C7] transition-colors cursor-pointer"
                            >
                              <Clock className="w-4 h-4" />
                              Pending
                            </button>
                            <button
                              onClick={() => handleStatusChange(volunteer.id, "Approved")}
                              className="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-[#059669] hover:bg-[#D1FAE5] transition-colors cursor-pointer"
                            >
                              <Check className="w-4 h-4" />
                              Approved
                            </button>
                            <button
                              onClick={() => handleStatusChange(volunteer.id, "Rejected")}
                              className="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-[#DC2626] hover:bg-[#FEE2E2] transition-colors cursor-pointer"
                            >
                              <X className="w-4 h-4" />
                              Reject
                            </button>
                          </div>
                        )}
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
