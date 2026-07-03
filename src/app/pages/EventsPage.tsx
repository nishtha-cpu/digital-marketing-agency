import { Plus, Calendar as CalendarIcon, MapPin, Users } from "lucide-react";

const events = [
  {
    id: 1,
    title: "Annual Scholarship Ceremony 2026",
    date: "2026-07-15",
    location: "New Delhi",
    attendees: 250,
    status: "Upcoming",
  },
  {
    id: 2,
    title: "Research Symposium",
    date: "2026-08-20",
    location: "Mumbai",
    attendees: 150,
    status: "Upcoming",
  },
  {
    id: 3,
    title: "Community Development Workshop",
    date: "2026-06-10",
    location: "Bangalore",
    attendees: 180,
    status: "Completed",
  },
];

export function EventsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">Events</h1>
          <p className="text-muted-foreground mt-1">
            Manage your organization's events and activities
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors">
          <Plus className="w-5 h-5" />
          Create Event
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Events</div>
          <div className="text-2xl font-semibold mt-1">45</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Upcoming</div>
          <div className="text-2xl font-semibold mt-1">8</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Attendees</div>
          <div className="text-2xl font-semibold mt-1">2,450</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">This Month</div>
          <div className="text-2xl font-semibold mt-1">3</div>
        </div>
      </div>

      <div className="space-y-4">
        {events.map((event) => (
          <div
            key={event.id}
            className="bg-card rounded-xl p-6 border border-border hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between">
              <div className="flex-1">
                <div className="flex items-center gap-3 mb-3">
                  <div className="w-12 h-12 rounded-lg bg-[#E8F5E9] flex items-center justify-center">
                    <CalendarIcon className="w-6 h-6 text-[#2E7D32]" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-foreground text-lg">
                      {event.title}
                    </h3>
                    <div className="flex items-center gap-4 mt-1 text-sm text-muted-foreground">
                      <span className="flex items-center gap-1">
                        <CalendarIcon className="w-4 h-4" />
                        {event.date}
                      </span>
                      <span className="flex items-center gap-1">
                        <MapPin className="w-4 h-4" />
                        {event.location}
                      </span>
                      <span className="flex items-center gap-1">
                        <Users className="w-4 h-4" />
                        {event.attendees} attendees
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <span
                className={`px-3 py-1 text-sm rounded-full ${
                  event.status === "Upcoming"
                    ? "bg-[#DBEAFE] text-[#1E40AF]"
                    : "bg-[#D1FAE5] text-[#059669]"
                }`}
              >
                {event.status}
              </span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
