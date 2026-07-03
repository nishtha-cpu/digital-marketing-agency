import { useState, useEffect } from "react";
import { Mail, Search, Trash2, Edit2, Check, X, ToggleLeft, ToggleRight } from "lucide-react";
import { storage, Subscriber } from "../utils/storage";

export function NewsletterPage() {
  const [subscribers, setSubscribers] = useState<Subscriber[]>([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);

  // Edit form states
  const [editingId, setEditingId] = useState<string | null>(null);
  const [editName, setEditName] = useState("");
  const [editEmail, setEditEmail] = useState("");
  const [editStatus, setEditStatus] = useState<"Active" | "Inactive">("Active");
  const [editError, setEditError] = useState("");

  useEffect(() => {
    setSubscribers(storage.getSubscribers());
  }, []);

  const handleEditClick = (sub: Subscriber) => {
    setEditingId(sub.id);
    setEditName(sub.name);
    setEditEmail(sub.email);
    setEditStatus(sub.status);
    setIsEditModalOpen(true);
  };

  const handleSaveEdit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!editName || !editEmail) {
      setEditError("Both name and email are required.");
      return;
    }

    const updated = subscribers.map((sub) =>
      sub.id === editingId
        ? { ...sub, name: editName, email: editEmail, status: editStatus }
        : sub
    );
    setSubscribers(updated);
    storage.saveSubscribers(updated);

    setEditingId(null);
    setEditError("");
    setIsEditModalOpen(false);
  };

  const toggleStatus = (id: string) => {
    const updated = subscribers.map((sub) =>
      sub.id === id
        ? { ...sub, status: (sub.status === "Active" ? "Inactive" : "Active") as "Active" | "Inactive" }
        : sub
    );
    setSubscribers(updated);
    storage.saveSubscribers(updated);
  };

  const handleDeleteSubscriber = (id: string) => {
    const updated = subscribers.filter((sub) => sub.id !== id);
    setSubscribers(updated);
    storage.saveSubscribers(updated);
  };

  const filteredSubscribers = subscribers.filter(
    (s) =>
      s.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      s.email.toLowerCase().includes(searchQuery.toLowerCase())
  );

  // Dynamic stats
  const totalSubscribers = subscribers.length;
  const activeCount = subscribers.filter((s) => s.status === "Active").length;
  const inactiveCount = totalSubscribers - activeCount;

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">
            Newsletter Subscribers
          </h1>
          <p className="text-muted-foreground mt-1">
            Manage your newsletter subscribers and subscription statuses
          </p>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Subscribers</div>
          <div className="text-2xl font-semibold mt-1">{totalSubscribers}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Active</div>
          <div className="text-2xl font-semibold mt-1 text-[#2E7D32]">{activeCount}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Inactive</div>
          <div className="text-2xl font-semibold mt-1 text-red-500">{inactiveCount}</div>
        </div>
      </div>

      {/* Toolbar */}
      <div className="bg-card rounded-xl p-4 border border-border">
        <div className="relative">
          <Search className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
          <input
            type="text"
            placeholder="Search subscribers..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="pl-10 pr-4 py-2 w-full md:w-80 bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm text-foreground"
          />
        </div>
      </div>

      {/* Subscribers Table Card */}
      <div className="bg-card rounded-xl border border-border overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-accent border-b border-border">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Subscriber Name
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Email
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Subscribed Date
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Status
                </th>
                <th className="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border">
              {filteredSubscribers.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-12 text-center text-muted-foreground text-sm">
                    No subscribers found.
                  </td>
                </tr>
              ) : (
                filteredSubscribers.map((sub) => (
                  <tr key={sub.id} className="hover:bg-accent/50 transition-colors">
                    <td className="px-6 py-4 font-medium text-foreground">{sub.name}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{sub.email}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{sub.subscribed}</td>
                    <td className="px-6 py-4">
                      <button
                        onClick={() => toggleStatus(sub.id)}
                        className={`flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-full cursor-pointer transition-colors ${
                          sub.status === "Active"
                            ? "bg-[#D1FAE5] text-[#059669] hover:bg-[#A7F3D0]"
                            : "bg-[#FEE2E2] text-[#DC2626] hover:bg-[#FCA5A5]"
                        }`}
                        title="Click to toggle status"
                      >
                        {sub.status === "Active" ? "Active" : "Inactive"}
                      </button>
                    </td>
                    <td className="px-6 py-4 text-right flex justify-end gap-2">
                      <button
                        onClick={() => handleEditClick(sub)}
                        className="p-1.5 hover:bg-accent rounded-lg text-blue-500 hover:text-blue-700 transition-colors cursor-pointer"
                        title="Edit subscriber"
                      >
                        <Edit2 className="w-4 h-4" />
                      </button>
                      <button
                        onClick={() => handleDeleteSubscriber(sub.id)}
                        className="p-1.5 hover:bg-accent rounded-lg text-red-500 hover:text-red-700 transition-colors cursor-pointer"
                        title="Delete subscriber"
                      >
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Edit Subscriber Modal */}
      {isEditModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
          <div className="bg-card w-full max-w-md rounded-xl border border-border shadow-lg overflow-hidden animate-in fade-in-50 duration-200">
            <div className="flex items-center justify-between p-6 border-b border-border">
              <h3 className="font-semibold text-foreground text-lg">Edit Subscriber</h3>
              <button
                onClick={() => setIsEditModalOpen(false)}
                className="p-1.5 hover:bg-accent rounded-lg text-muted-foreground transition-colors cursor-pointer"
              >
                <X className="w-5 h-5" />
              </button>
            </div>
            <form onSubmit={handleSaveEdit} className="p-6 space-y-4">
              {editError && <p className="text-red-500 text-sm">{editError}</p>}
              <div className="space-y-1">
                <label className="text-xs font-semibold text-muted-foreground uppercase">
                  Name
                </label>
                <input
                  type="text"
                  value={editName}
                  onChange={(e) => setEditName(e.target.value)}
                  className="w-full px-3 py-2 bg-accent rounded-lg border border-border focus:border-primary focus:outline-none text-sm text-foreground"
                />
              </div>
              <div className="space-y-1">
                <label className="text-xs font-semibold text-muted-foreground uppercase">
                  Email
                </label>
                <input
                  type="email"
                  value={editEmail}
                  onChange={(e) => setEditEmail(e.target.value)}
                  className="w-full px-3 py-2 bg-accent rounded-lg border border-border focus:border-primary focus:outline-none text-sm text-foreground"
                />
              </div>
              <div className="space-y-1">
                <label className="text-xs font-semibold text-muted-foreground uppercase">
                  Status
                </label>
                <select
                  value={editStatus}
                  onChange={(e) => setEditStatus(e.target.value as "Active" | "Inactive")}
                  className="w-full px-3 py-2 bg-accent rounded-lg border border-border focus:border-primary focus:outline-none text-sm text-foreground"
                >
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
              <div className="flex gap-3 pt-4 justify-end">
                <button
                  type="button"
                  onClick={() => setIsEditModalOpen(false)}
                  className="px-4 py-2 bg-accent hover:bg-accent/80 text-foreground rounded-lg text-sm transition-colors cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#2E7D32] hover:bg-[#1B5E20] text-white rounded-lg text-sm transition-colors cursor-pointer font-semibold"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
