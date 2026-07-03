import { Plus, Star, Edit, Trash2 } from "lucide-react";

const testimonials = [
  {
    id: 1,
    name: "Priya Sharma",
    role: "Scholarship Recipient",
    rating: 5,
    content:
      "PrayogBharti Foundation has been instrumental in my educational journey. The scholarship program helped me pursue my dreams.",
    date: "2026-06-15",
  },
  {
    id: 2,
    name: "Rajesh Kumar",
    role: "Research Fellow",
    rating: 5,
    content:
      "The research fellowship provided by PrayogBharti gave me the opportunity to work on meaningful projects that make a real difference.",
    date: "2026-06-10",
  },
  {
    id: 3,
    name: "Amit Patel",
    role: "Mentee",
    rating: 5,
    content:
      "The mentorship program connected me with industry experts who guided me through my career choices.",
    date: "2026-06-05",
  },
];

export function TestimonialsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">
            Testimonials
          </h1>
          <p className="text-muted-foreground mt-1">
            Manage feedback and testimonials from your community
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors">
          <Plus className="w-5 h-5" />
          Add Testimonial
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Reviews</div>
          <div className="text-2xl font-semibold mt-1">89</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Average Rating</div>
          <div className="text-2xl font-semibold mt-1">4.8</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">This Month</div>
          <div className="text-2xl font-semibold mt-1">12</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Pending</div>
          <div className="text-2xl font-semibold mt-1">5</div>
        </div>
      </div>

      <div className="space-y-4">
        {testimonials.map((testimonial) => (
          <div
            key={testimonial.id}
            className="bg-card rounded-xl p-6 border border-border hover:shadow-md transition-shadow"
          >
            <div className="flex items-start justify-between mb-4">
              <div className="flex items-start gap-4">
                <div className="w-12 h-12 rounded-full bg-[#2E7D32] flex items-center justify-center text-white font-semibold">
                  {testimonial.name.charAt(0)}
                </div>
                <div>
                  <h3 className="font-semibold text-foreground">
                    {testimonial.name}
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    {testimonial.role}
                  </p>
                  <div className="flex items-center gap-1 mt-2">
                    {Array.from({ length: testimonial.rating }).map((_, i) => (
                      <Star
                        key={i}
                        className="w-4 h-4 fill-[#F59E0B] text-[#F59E0B]"
                      />
                    ))}
                  </div>
                </div>
              </div>
              <div className="flex items-center gap-2">
                <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                  <Edit className="w-4 h-4 text-muted-foreground" />
                </button>
                <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                  <Trash2 className="w-4 h-4 text-[#EF4444]" />
                </button>
              </div>
            </div>
            <p className="text-muted-foreground">{testimonial.content}</p>
            <div className="mt-4 text-sm text-muted-foreground">
              {testimonial.date}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
