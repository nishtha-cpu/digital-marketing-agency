import { useState, useEffect } from "react";
import { Mail, Star, Trash2, Archive, Reply, Search, X } from "lucide-react";
import { storage, ContactEnquiry } from "../utils/storage";

export function ContactPage() {
  const [contacts, setContacts] = useState<ContactEnquiry[]>([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [filterMode, setFilterMode] = useState<"All" | "Unread" | "Starred">("All");

  useEffect(() => {
    setContacts(storage.getContacts());
  }, []);

  const handleDeleteEnquiry = (id: string) => {
    const updated = contacts.filter((c) => c.id !== id);
    setContacts(updated);
    storage.saveContacts(updated);
  };

  const toggleStar = (id: string) => {
    const updated = contacts.map((c) =>
      c.id === id ? { ...c, starred: !c.starred } : c
    );
    setContacts(updated);
    storage.saveContacts(updated);
  };

  const toggleReadStatus = (id: string) => {
    const updated = contacts.map((c) =>
      c.id === id ? { ...c, status: (c.status === "Unread" ? "Read" : "Unread") as "Read" | "Unread" } : c
    );
    setContacts(updated);
    storage.saveContacts(updated);
  };

  const filteredContacts = contacts.filter((c) => {
    const matchesSearch =
      c.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.mail.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.enqueri.toLowerCase().includes(searchQuery.toLowerCase()) ||
      c.phone.includes(searchQuery);

    if (!matchesSearch) return false;

    if (filterMode === "Unread") return c.status === "Unread";
    if (filterMode === "Starred") return c.starred;
    return true;
  });

  // Stats
  const totalEnquiries = contacts.length;
  const unreadCount = contacts.filter((c) => c.status === "Unread").length;
  const starredCount = contacts.filter((c) => c.starred).length;

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">Contact Enquiries</h1>
          <p className="text-muted-foreground mt-1">
            Manage contact form submissions and inquiries
          </p>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Enquiries</div>
          <div className="text-2xl font-semibold mt-1">{totalEnquiries}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Unread</div>
          <div className="text-2xl font-semibold mt-1">{unreadCount}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Starred</div>
          <div className="text-2xl font-semibold mt-1">{starredCount}</div>
        </div>
      </div>

      {/* Filters Toolbar */}
      <div className="bg-card rounded-xl p-4 border border-border">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex gap-2">
            {(["All", "Unread", "Starred"] as const).map((mode) => (
              <button
                key={mode}
                onClick={() => setFilterMode(mode)}
                className={`px-4 py-2 rounded-lg text-sm transition-colors cursor-pointer ${
                  filterMode === mode
                    ? "bg-[#2E7D32] text-white font-medium"
                    : "hover:bg-accent text-muted-foreground hover:text-foreground"
                }`}
              >
                {mode}
              </button>
            ))}
          </div>
          <div className="relative">
            <Search className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
            <input
              type="text"
              placeholder="Search enquiries..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="pl-10 pr-4 py-2 w-full md:w-64 bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm text-foreground"
            />
          </div>
        </div>
      </div>

      {/* Enquiries Cards List */}
      <div className="space-y-3">
        {filteredContacts.length === 0 ? (
          <div className="bg-card rounded-xl p-12 border border-border text-center text-muted-foreground text-sm">
            No enquiries found. Click "Add Enquiry" to submit a simulated inquiry.
          </div>
        ) : (
          filteredContacts.map((contact) => (
            <div
              key={contact.id}
              className={`bg-card rounded-xl p-6 border border-border hover:shadow-md transition-shadow ${
                contact.status === "Unread" ? "border-l-4 border-l-[#2E7D32] bg-accent/10" : ""
              }`}
            >
              <div className="flex flex-col md:flex-row items-start justify-between gap-4">
                <div className="flex items-start gap-4 flex-1">
                  <div className="w-10 h-10 rounded-full bg-[#2E7D32] flex items-center justify-center text-white font-semibold flex-shrink-0">
                    {contact.name.charAt(0)}
                  </div>
                  <div className="flex-1 space-y-1">
                    <div className="flex flex-wrap items-center gap-3">
                      <h3 className="font-semibold text-foreground">{contact.name}</h3>
                      <span className="text-sm text-muted-foreground">{contact.mail}</span>
                      <span className="text-xs px-2.5 py-0.5 rounded-full bg-accent text-muted-foreground">
                        {contact.phone}
                      </span>
                      {contact.status === "Unread" && (
                        <span className="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                          New
                        </span>
                      )}
                    </div>
                    <div className="text-sm text-foreground bg-accent/20 p-3 rounded-lg border border-border/50">
                      <p className="font-medium text-xs text-muted-foreground mb-1 uppercase tracking-wider">
                        Enquiry
                      </p>
                      <p className="whitespace-pre-wrap">{contact.enqueri}</p>
                    </div>
                    <div className="text-xs text-muted-foreground pt-1">{contact.date}</div>
                  </div>
                </div>
                <div className="flex items-center gap-2 self-end md:self-start">
                  <button
                    onClick={() => toggleStar(contact.id)}
                    className={`p-2 rounded-lg transition-colors cursor-pointer ${
                      contact.starred ? "text-amber-500" : "hover:bg-accent text-muted-foreground"
                    }`}
                    title={contact.starred ? "Unstar" : "Star"}
                  >
                    <Star className={`w-4 h-4 ${contact.starred ? "fill-amber-500" : ""}`} />
                  </button>
                  <button
                    onClick={() => toggleReadStatus(contact.id)}
                    className="p-2 hover:bg-accent rounded-lg text-muted-foreground transition-colors cursor-pointer text-xs font-semibold"
                    title={contact.status === "Unread" ? "Mark as Read" : "Mark as Unread"}
                  >
                    {contact.status === "Unread" ? "Mark Read" : "Mark Unread"}
                  </button>
                  <button
                    onClick={() => handleDeleteEnquiry(contact.id)}
                    className="p-2 hover:bg-accent rounded-lg text-red-500 hover:text-red-700 transition-colors cursor-pointer"
                    title="Delete enquiry"
                  >
                    <Trash2 className="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          ))
        )}
      </div>

    </div>
  );
}
