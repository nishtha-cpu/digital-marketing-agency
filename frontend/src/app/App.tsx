import { useState, useEffect } from "react";
import { Menu, X, ArrowRight, BookOpen, Users, Star, Heart, Award, Globe, ChevronDown, Mail, Phone, MapPin, Quote } from "lucide-react";

function smoothScrollTo(href: string) {
  const id = href.replace("#", "");
  const el = document.getElementById(id);
  if (!el) return;
  const offset = 104; // orange bar (~32px) + white nav (~72px)
  const top = el.getBoundingClientRect().top + window.scrollY - offset;
  window.scrollTo({ top, behavior: "smooth" });
}

const NAV_LINKS = [
  { label: "Home", href: "#home" },
  { label: "About", href: "#about" },
  { label: "Programs", href: "#programs" },
  { label: "Services", href: "#services" },
  { label: "Impact", href: "#impact" },
  { label: "Blog", href: "#blog" },
  { label: "Contact", href: "#contact" },
];

const SERVICES = [
  {
    icon: Award,
    title: "Scholarships",
    desc: "Providing financial assistance for deserving students to pursue their education without barriers.",
    img: "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&h=400&fit=crop&auto=format",
  },
  {
    icon: BookOpen,
    title: "Coaching Services",
    desc: "Offering personalized coaching to enhance academic skills and performance in STEM disciplines.",
    img: "https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=600&h=400&fit=crop&auto=format",
  },
  {
    icon: Users,
    title: "Mentorship Programs",
    desc: "Connecting students with experienced mentors for guidance, support, and career direction.",
    img: "https://images.unsplash.com/photo-1542323228-002ac256e7b8?w=600&h=400&fit=crop&auto=format",
  },
  {
    icon: Globe,
    title: "Community Outreach",
    desc: "Engaging with communities to promote educational opportunities and inspire local talent.",
    img: "https://images.unsplash.com/photo-1694286066866-4324f80d7906?w=600&h=400&fit=crop&auto=format",
  },
];

const STATS = [
  { value: "20+", label: "Years of Experience" },
  { value: "40+", label: "Team Members" },
  { value: "98%", label: "Satisfied Students" },
  { value: "500+", label: "Programs Completed" },
];

const TESTIMONIALS = [
  {
    name: "Anita Sharma",
    role: "Scholarship Recipient, 2022",
    text: "Prayogbharti Foundation has changed my life by providing educational opportunities I never thought possible. I am truly grateful for their support and guidance.",
    rating: 5,
  },
  {
    name: "Raj Patel",
    role: "Mentorship Graduate, 2023",
    text: "The mentorship program opened doors I didn't know existed. Their guidance has been invaluable to my academic success and future career in engineering.",
    rating: 5,
  },
  {
    name: "Priya Nair",
    role: "STEM Program Alumna",
    text: "Thanks to the coaching services, I cleared my competitive exams with confidence. The teachers here genuinely care about every student's growth.",
    rating: 5,
  },
];

const WHY_US = [
  {
    num: "01",
    title: "Inclusive Learning",
    desc: "We promote inclusivity by ensuring education is accessible to all, regardless of background, empowering every individual to achieve their potential.",
  },
  {
    num: "02",
    title: "Innovative Programs",
    desc: "Our programs embrace modern teaching methods and technologies to enhance learning experiences in STEM disciplines.",
  },
  {
    num: "03",
    title: "Community Impact",
    desc: "We foster local talent and contribute to the growth and development of the communities we serve.",
  },
  {
    num: "04",
    title: "Mentorship Opportunities",
    desc: "By connecting students with experienced mentors, we encourage personal and professional growth at every stage.",
  },
];

const BLOG_POSTS = [
  {
    date: "June 5, 2025",
    category: "Education",
    title: "Breaking Barriers: How STEM Education Is Changing Rural India",
    excerpt: "Across villages in Maharashtra and Rajasthan, a quiet revolution is underway — one textbook at a time.",
    img: "https://images.unsplash.com/flagged/photo-1574097656146-0b43b7660cb6?w=600&h=400&fit=crop&auto=format",
  },
  {
    date: "May 18, 2025",
    category: "Mentorship",
    title: "Meet the Mentors: Professionals Who Give Back to the Community",
    excerpt: "From IIT graduates to doctors and engineers — the volunteers who spend weekends shaping young minds.",
    img: "https://images.unsplash.com/photo-1761666520005-3ffcf13e74c8?w=600&h=400&fit=crop&auto=format",
  },
  {
    date: "April 30, 2025",
    category: "Impact",
    title: "Scholarship Stories: The Faces Behind Our 2024 Annual Report",
    excerpt: "We sat down with five scholarship recipients to understand what financial support truly means.",
    img: "https://images.unsplash.com/photo-1652648265326-73317a42c43d?w=600&h=400&fit=crop&auto=format",
  },
];

const iconMap: Record<string, any> = {
  Award: Award,
  BookOpen: BookOpen,
  Users: Users,
  Globe: Globe,
  Star: Star,
  Heart: Heart,
};

const defaultImages = [
  "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&h=400&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=600&h=400&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1542323228-002ac256e7b8?w=600&h=400&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1694286066866-4324f80d7906?w=600&h=400&fit=crop&auto=format"
];

const defaultBlogImages = [
  "https://images.unsplash.com/flagged/photo-1574097656146-0b43b7660cb6?w=600&h=400&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1761666520005-3ffcf13e74c8?w=600&h=400&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1652648265326-73317a42c43d?w=600&h=400&fit=crop&auto=format"
];

export default function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [activeTestimonial, setActiveTestimonial] = useState(0);

  // Dynamic API state
  const [blogs, setBlogs] = useState<any[]>([]);
  const [services, setServices] = useState<any[]>([]);
  const [blogsLoading, setBlogsLoading] = useState(true);
  const [servicesLoading, setServicesLoading] = useState(true);

  // Form State
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    email: "",
    interest: "Donating / Sponsoring",
    message: ""
  });
  const [formStatus, setFormStatus] = useState<{
    type: "idle" | "submitting" | "success" | "error";
    message: string;
  }>({ type: "idle", message: "" });

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener("scroll", onScroll);
    
    // Fetch Services
    const fetchServices = async () => {
      try {
        const res = await fetch("http://localhost:5000/api/services");
        const data = await res.json();
        if (data.success && data.data && data.data.length > 0) {
          setServices(data.data);
        } else {
          setServices(SERVICES);
        }
      } catch (err) {
        console.error("Error fetching services, using fallback", err);
        setServices(SERVICES);
      } finally {
        setServicesLoading(false);
      }
    };

    // Fetch Blogs
    const fetchBlogs = async () => {
      try {
        const res = await fetch("http://localhost:5000/api/blogs");
        const data = await res.json();
        if (data.success && data.data && data.data.length > 0) {
          setBlogs(data.data);
        } else {
          setBlogs(BLOG_POSTS);
        }
      } catch (err) {
        console.error("Error fetching blogs, using fallback", err);
        setBlogs(BLOG_POSTS);
      } finally {
        setBlogsLoading(false);
      }
    };

    fetchServices();
    fetchBlogs();

    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleFormSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.firstName || !formData.email || !formData.message) {
      setFormStatus({ type: "error", message: "Please fill in all required fields (First Name, Email, and Message)." });
      return;
    }

    setFormStatus({ type: "submitting", message: "Sending your message..." });

    try {
      const res = await fetch("http://localhost:5000/api/leads", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          name: `${formData.firstName} ${formData.lastName}`.trim(),
          email: formData.email,
          serviceInterest: formData.interest,
          message: formData.message
        })
      });

      const data = await res.json();

      if (data.success) {
        setFormStatus({ type: "success", message: "Your message has been sent successfully!" });
        setFormData({
          firstName: "",
          lastName: "",
          email: "",
          interest: "Donating / Sponsoring",
          message: ""
        });
      } else {
        setFormStatus({ type: "error", message: data.message || "Failed to send message." });
      }
    } catch (err) {
      console.error("Error submitting contact form", err);
      setFormStatus({ type: "error", message: "An error occurred. Please try again later." });
    }
  };

  return (
    <div
      className="min-h-screen bg-background text-foreground"
      style={{ fontFamily: "'Nunito', sans-serif" }}
    >
      {/* TOP INFO BAR */}
      <div className="fixed top-0 left-0 right-0 z-50 bg-[#F5A623] text-white text-xs font-semibold px-6 lg:px-10 py-2 flex flex-wrap items-center justify-start gap-4 sm:gap-8">
        <a href="mailto:contact@prayogbharti.org" className="flex items-center gap-1.5 hover:text-white/80 transition-colors">
          <Mail size={12} />
          contact@prayogbharti.org
        </a>
        <a href="tel:+919876543210" className="flex items-center gap-1.5 hover:text-white/80 transition-colors">
          <Phone size={12} />
          +91 98765 43210
        </a>
      </div>

      {/* NAV */}
      <nav
        className={`fixed left-0 right-0 z-40 transition-all duration-300 bg-white border-b-2 border-primary/40 top-[32px] ${scrolled ? "shadow-lg" : "shadow-md"
          }`}
      >
        <div className="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-18 py-4">
          <a href="#home" className="flex items-center gap-3 group">
            <div className="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
              <span className="text-white font-bold text-sm" style={{ fontFamily: "'Playfair Display', serif" }}>PB</span>
            </div>
            <div>
              <div
                className="text-lg font-bold leading-tight text-foreground"
                style={{ fontFamily: "'Playfair Display', serif" }}
              >
                Prayogbharti
              </div>
              <div className="text-xs text-muted-foreground tracking-widest uppercase">Foundation</div>
            </div>
          </a>

          <div className="hidden lg:flex items-center gap-8">
            {NAV_LINKS.map((link) => (
              <a
                key={link.label}
                href={link.href}
                onClick={(e) => { e.preventDefault(); smoothScrollTo(link.href); }}
                className="text-sm font-medium text-foreground/70 hover:text-primary transition-colors duration-200"
              >
                {link.label}
              </a>
            ))}
            <a
              href="#contact"
              onClick={(e) => { e.preventDefault(); smoothScrollTo("#contact"); }}
              className="ml-4 bg-primary text-primary-foreground text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-accent transition-colors duration-200"
            >
              Get Involved
            </a>
          </div>

          <button
            className="lg:hidden p-2 text-foreground"
            onClick={() => setMenuOpen(!menuOpen)}
            aria-label="Toggle menu"
          >
            {menuOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>

        {menuOpen && (
          <div className="lg:hidden bg-white border-t border-border px-6 py-6 flex flex-col gap-4">
            {NAV_LINKS.map((link) => (
              <a
                key={link.label}
                href={link.href}
                className="text-base font-medium text-foreground/80 hover:text-primary transition-colors"
                onClick={(e) => { e.preventDefault(); smoothScrollTo(link.href); setMenuOpen(false); }}
              >
                {link.label}
              </a>
            ))}
            <a
              href="#contact"
              className="mt-2 bg-primary text-white text-center font-semibold px-5 py-3 rounded-full"
              onClick={(e) => { e.preventDefault(); smoothScrollTo("#contact"); setMenuOpen(false); }}
            >
              Get Involved
            </a>
          </div>
        )}
      </nav>

      {/* HERO */}
      <section id="home" className="relative min-h-screen flex items-center overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage:
              "url(https://images.unsplash.com/flagged/photo-1574097656146-0b43b7660cb6?w=1600&h=900&fit=crop&auto=format)",
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-r from-[#0d3320]/90 via-[#0d3320]/65 to-transparent" />

        <div className="relative max-w-7xl mx-auto px-6 lg:px-10 pt-24 pb-16 grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <div className="inline-flex items-center gap-2 bg-primary/20 border border-primary/30 text-primary-foreground/90 text-xs font-semibold uppercase tracking-widest px-4 py-1.5 rounded-full mb-8"
              style={{ color: "#F5A623" }}>
              <Heart size={12} fill="currentColor" />
              Non-Profit Education Initiative
            </div>
            <h1
              className="text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.1] mb-6"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              Empowering
              <span className="block italic text-[#F5A623]">Lives Through</span>
              Education
            </h1>
            <p className="text-lg text-white/80 max-w-md mb-10 leading-relaxed">
              Join us in making quality education accessible to all — especially in STEM fields for underprivileged individuals across India.
            </p>
            <div className="flex flex-wrap gap-4">
              <a
                href="#contact"
                className="inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-accent transition-colors duration-200 text-sm"
              >
                Get Involved <ArrowRight size={16} />
              </a>
              <a
                href="#about"
                className="inline-flex items-center gap-2 border border-white/40 text-white font-semibold px-7 py-3.5 rounded-full hover:bg-white/10 transition-colors duration-200 text-sm"
              >
                Our Mission
              </a>
            </div>

            <div className="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-lg lg:max-w-none">
              {STATS.map((s) => (
                <div key={s.label} className="text-center lg:text-left">
                  <div
                    className="text-3xl font-bold text-[#F5A623]"
                    style={{ fontFamily: "'Playfair Display', serif" }}
                  >
                    {s.value}
                  </div>
                  <div className="text-xs text-white/60 mt-1 leading-tight">{s.label}</div>
                </div>
              ))}
            </div>
          </div>
        </div>

        <a
          href="#about"
          className="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/60 hover:text-white transition-colors animate-bounce"
        >
          <ChevronDown size={28} />
        </a>
      </section>

      {/* ABOUT */}
      <section id="about" className="py-24 bg-background">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16 items-center">
          <div className="relative">
            <div className="rounded-2xl overflow-hidden aspect-[4/5] bg-muted">
              <img
                src="https://images.unsplash.com/photo-1652648265326-73317a42c43d?w=700&h=900&fit=crop&auto=format"
                alt="Students learning together"
                className="w-full h-full object-cover"
              />
            </div>
            <div className="absolute -bottom-6 -right-6 bg-primary text-white rounded-2xl p-6 shadow-xl max-w-[200px]">
              <div
                className="text-4xl font-bold"
                style={{ fontFamily: "'Playfair Display', serif" }}
              >
                20+
              </div>
              <div className="text-sm text-white/80 mt-1">Years transforming lives through education</div>
            </div>
            <div className="absolute top-6 -left-6 bg-[#F5A623] rounded-2xl p-4 shadow-xl">
              <Heart size={28} className="text-white" fill="white" />
            </div>
          </div>

          <div>
            <div className="text-primary text-sm font-bold uppercase tracking-widest mb-4">Our Mission</div>
            <h2
              className="text-4xl lg:text-5xl font-bold text-foreground mb-6 leading-[1.15]"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              The Inspiring Journey Behind Prayogbharti Foundation
            </h2>
            <p className="text-muted-foreground text-lg mb-6 leading-relaxed">
              Founded to inspire change, our organization harnesses the power of education to uplift underprivileged individuals and create opportunities for a better future. We believe that every child, regardless of economic background, deserves access to quality learning.
            </p>
            <p className="text-muted-foreground leading-relaxed mb-10">
              Over two decades, we have built a network of educators, mentors, and volunteers who share a common vision — a society where talent and hard work determine one&apos;s future, not the circumstances of birth.
            </p>
            <a
              href="#programs"
              className="inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-accent transition-colors duration-200 text-sm"
            >
              Explore Programs <ArrowRight size={16} />
            </a>
          </div>
        </div>
      </section>

      {/* SERVICES */}
      <section id="services" className="py-24 bg-secondary">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="text-center mb-16">
            <div className="text-primary text-sm font-bold uppercase tracking-widest mb-4">Our Services</div>
            <h2
              className="text-4xl lg:text-5xl font-bold text-foreground mb-4"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              Comprehensive Educational
              <span className="block italic">Support Programs</span>
            </h2>
            <p className="text-muted-foreground max-w-xl mx-auto text-lg">
              Programs including scholarships, mentorship, coaching, and resources designed to empower students of all ages.
            </p>
          </div>

          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {services.map((s, index) => {
              const IconComponent = iconMap[s.icon] || s.icon || Award;
              const imgUrl = s.img || defaultImages[index % defaultImages.length];
              return (
                <div
                  key={s.name || s.title}
                  className="bg-card rounded-2xl overflow-hidden group hover:shadow-lg transition-shadow duration-300 border border-border"
                >
                  <div className="relative h-48 bg-muted overflow-hidden">
                    <img
                      src={imgUrl}
                      alt={s.name || s.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-foreground/40 to-transparent" />
                    <div className="absolute bottom-4 left-4 bg-primary rounded-xl p-2">
                      <IconComponent size={20} className="text-white" />
                    </div>
                  </div>
                  <div className="p-5">
                    <h3
                      className="text-lg font-bold text-foreground mb-2"
                      style={{ fontFamily: "'Playfair Display', serif" }}
                    >
                      {s.name || s.title}
                    </h3>
                    <p className="text-muted-foreground text-sm leading-relaxed">{s.description || s.desc}</p>
                    <button className="mt-4 text-primary text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all duration-200">
                      Read More <ArrowRight size={14} />
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* PROGRAMS / WHY CHOOSE US */}
      <section id="programs" className="py-24 bg-background">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16 items-center">
          <div>
            <div className="text-primary text-sm font-bold uppercase tracking-widest mb-4">Why Choose Us</div>
            <h2
              className="text-4xl lg:text-5xl font-bold text-foreground mb-4 leading-[1.15]"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              Commitment to
              <span className="block italic text-primary">Excellence</span>
            </h2>
            <p className="text-muted-foreground text-lg mb-10 leading-relaxed">
              We don&apos;t just provide education — we build futures. Each program is thoughtfully designed to address real barriers and create lasting impact.
            </p>

            <div className="space-y-6">
              {WHY_US.map((item) => (
                <div key={item.num} className="flex gap-5 group">
                  <div className="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm group-hover:bg-primary group-hover:text-white transition-colors duration-300"
                    style={{ fontFamily: "'DM Mono', monospace" }}>
                    {item.num}
                  </div>
                  <div>
                    <h4
                      className="font-bold text-foreground mb-1"
                      style={{ fontFamily: "'Playfair Display', serif" }}
                    >
                      {item.title}
                    </h4>
                    <p className="text-muted-foreground text-sm leading-relaxed">{item.desc}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="relative">
            <div className="rounded-2xl overflow-hidden aspect-square bg-muted">
              <img
                src="https://images.unsplash.com/photo-1761666520005-3ffcf13e74c8?w=700&h=700&fit=crop&auto=format"
                alt="Diverse group of students and mentors"
                className="w-full h-full object-cover"
              />
            </div>
            <div className="absolute inset-0 rounded-2xl ring-1 ring-inset ring-border" />
            <div className="absolute -bottom-8 left-8 right-8 bg-white rounded-2xl p-5 shadow-xl border border-border flex items-center gap-4">
              <div className="w-12 h-12 bg-[#F5A623] rounded-full flex items-center justify-center flex-shrink-0">
                <Star size={20} className="text-white" fill="white" />
              </div>
              <div>
                <div className="text-foreground font-bold text-sm">4.8 Google Rating</div>
                <div className="flex gap-0.5 mt-1">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} size={12} className="text-[#F5A623]" fill="#F5A623" />
                  ))}
                </div>
                <div className="text-muted-foreground text-xs mt-0.5">from 2,000+ happy students</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* IMPACT STRIP */}
      <section id="impact" className="py-20 bg-primary">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {STATS.map((s) => (
              <div key={s.label} className="text-center">
                <div
                  className="text-5xl font-bold text-white mb-2"
                  style={{ fontFamily: "'Playfair Display', serif" }}
                >
                  {s.value}
                </div>
                <div className="text-white/70 text-sm tracking-wide">{s.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* TESTIMONIALS */}
      <section className="py-24 bg-secondary">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="text-center mb-16">
            <div className="text-primary text-sm font-bold uppercase tracking-widest mb-4">What They Say</div>
            <h2
              className="text-4xl lg:text-5xl font-bold text-foreground"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              Voices from Our
              <span className="block italic">Community</span>
            </h2>
          </div>

          <div className="grid lg:grid-cols-3 gap-6">
            {TESTIMONIALS.map((t, i) => (
              <div
                key={t.name}
                className={`bg-card border border-border rounded-2xl p-7 transition-shadow duration-300 ${i === activeTestimonial ? "shadow-lg ring-2 ring-primary/20" : "hover:shadow-md"}`}
                onClick={() => setActiveTestimonial(i)}
              >
                <Quote size={28} className="text-primary/30 mb-4" />
                <p className="text-foreground/80 leading-relaxed mb-6 text-sm">{t.text}</p>
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                    {t.name[0]}
                  </div>
                  <div>
                    <div className="font-bold text-foreground text-sm">{t.name}</div>
                    <div className="text-muted-foreground text-xs">{t.role}</div>
                  </div>
                  <div className="ml-auto flex gap-0.5">
                    {[...Array(t.rating)].map((_, j) => (
                      <Star key={j} size={12} className="text-[#F5A623]" fill="#F5A623" />
                    ))}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* BLOG */}
      <section id="blog" className="py-24 bg-background">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-14 gap-4">
            <div>
              <div className="text-primary text-sm font-bold uppercase tracking-widest mb-4">Latest News</div>
              <h2
                className="text-4xl font-bold text-foreground"
                style={{ fontFamily: "'Playfair Display', serif" }}
              >
                Stories of
                <span className="italic"> Impact</span>
              </h2>
            </div>
            <a
              href="#blog"
              className="inline-flex items-center gap-2 text-primary font-semibold text-sm hover:gap-3 transition-all duration-200"
            >
              View all posts <ArrowRight size={14} />
            </a>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {blogs.map((post, index) => {
              const coverImg = post.coverImage || post.img || defaultBlogImages[index % defaultBlogImages.length];
              const category = post.tags && post.tags.length > 0 ? post.tags[0] : post.category || "General";
              const formattedDate = post.createdAt ? new Date(post.createdAt).toLocaleDateString("en-US", {
                month: "long",
                day: "numeric",
                year: "numeric"
              }) : post.date || "Recent";
              return (
                <article
                  key={post.title}
                  className="group bg-card border border-border rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300"
                >
                  <div className="relative h-52 bg-muted overflow-hidden">
                    <img
                      src={coverImg}
                      alt={post.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                    <span className="absolute top-4 left-4 bg-primary text-white text-xs font-bold uppercase tracking-wide px-3 py-1 rounded-full">
                      {category}
                    </span>
                  </div>
                  <div className="p-6">
                    <div className="text-muted-foreground text-xs mb-3"
                      style={{ fontFamily: "'DM Mono', monospace" }}>
                      {formattedDate}
                    </div>
                    <h3
                      className="text-lg font-bold text-foreground mb-3 leading-snug group-hover:text-primary transition-colors duration-200"
                      style={{ fontFamily: "'Playfair Display', serif" }}
                    >
                      {post.title}
                    </h3>
                    <p className="text-muted-foreground text-sm leading-relaxed">{post.summary || post.excerpt}</p>
                    <button className="mt-5 text-primary text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all duration-200">
                      Read more <ArrowRight size={14} />
                    </button>
                  </div>
                </article>
              );
            })}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-24 bg-foreground relative overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center opacity-10"
          style={{
            backgroundImage:
              "url(https://images.unsplash.com/photo-1542323228-002ac256e7b8?w=1600&h=600&fit=crop&auto=format)",
          }}
        />
        <div className="relative max-w-4xl mx-auto px-6 lg:px-10 text-center">
          <div className="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-8">
            <Heart size={28} className="text-white" fill="white" />
          </div>
          <h2
            className="text-4xl lg:text-5xl font-bold text-white mb-6"
            style={{ fontFamily: "'Playfair Display', serif" }}
          >
            Together, We Can Make
            <span className="block italic text-[#F5A623]">a Difference</span>
          </h2>
          <p className="text-white/70 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
            Your contribution can help provide scholarships and mentorship to deserving students, empowering them for a brighter future. Every donation counts.
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <a
              href="#contact"
              className="inline-flex items-center gap-2 bg-primary text-white font-semibold px-8 py-4 rounded-full hover:bg-accent transition-colors duration-200"
            >
              Donate Now <Heart size={16} fill="currentColor" />
            </a>
            <a
              href="#programs"
              className="inline-flex items-center gap-2 border border-white/30 text-white font-semibold px-8 py-4 rounded-full hover:bg-white/10 transition-colors duration-200"
            >
              Volunteer With Us
            </a>
          </div>
        </div>
      </section>

      {/* CONTACT */}
      <section id="contact" className="py-24 bg-background">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16">
          <div>
            <div className="text-primary text-sm font-bold uppercase tracking-widest mb-4">Get In Touch</div>
            <h2
              className="text-4xl lg:text-5xl font-bold text-foreground mb-6"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              Let&apos;s Start a
              <span className="block italic">Conversation</span>
            </h2>
            <p className="text-muted-foreground text-lg mb-10 leading-relaxed">
              Whether you want to donate, volunteer, apply for a scholarship, or simply learn more about our work — we&apos;d love to hear from you.
            </p>
            <div className="space-y-5">
              {[
                { icon: Mail, label: "Email Us", value: "contact@prayogbharti.org" },
                { icon: Phone, label: "Call Us", value: "+91 98765 43210" },
                { icon: MapPin, label: "Location", value: "India (Serving communities nationwide)" },
              ].map((item) => (
                <div key={item.label} className="flex items-start gap-4">
                  <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    <item.icon size={18} className="text-primary" />
                  </div>
                  <div>
                    <div className="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-0.5">{item.label}</div>
                    <div className="text-foreground font-medium">{item.value}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="bg-card border border-border rounded-2xl p-8">
            <h3
              className="text-2xl font-bold text-foreground mb-6"
              style={{ fontFamily: "'Playfair Display', serif" }}
            >
              Send Us a Message
            </h3>
            <form className="space-y-5" onSubmit={handleFormSubmit}>
              <div className="grid sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-semibold text-foreground mb-2">First Name *</label>
                  <input
                    type="text"
                    name="firstName"
                    value={formData.firstName}
                    onChange={handleInputChange}
                    placeholder="Anita"
                    required
                    className="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all"
                  />
                </div>
                <div>
                  <label className="block text-sm font-semibold text-foreground mb-2">Last Name</label>
                  <input
                    type="text"
                    name="lastName"
                    value={formData.lastName}
                    onChange={handleInputChange}
                    placeholder="Sharma"
                    className="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all"
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-semibold text-foreground mb-2">Email Address *</label>
                <input
                  type="email"
                  name="email"
                  value={formData.email}
                  onChange={handleInputChange}
                  placeholder="anita@example.com"
                  required
                  className="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold text-foreground mb-2">I am interested in…</label>
                <select
                  name="interest"
                  value={formData.interest}
                  onChange={handleInputChange}
                  className="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all"
                >
                  <option value="Donating / Sponsoring">Donating / Sponsoring</option>
                  <option value="Volunteering / Mentoring">Volunteering / Mentoring</option>
                  <option value="Applying for Scholarship">Applying for Scholarship</option>
                  <option value="Partnership / Collaboration">Partnership / Collaboration</option>
                  <option value="General Enquiry">General Enquiry</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-semibold text-foreground mb-2">Message *</label>
                <textarea
                  name="message"
                  value={formData.message}
                  onChange={handleInputChange}
                  rows={4}
                  placeholder="Tell us a little about yourself and how you'd like to get involved…"
                  required
                  className="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all resize-none"
                />
              </div>

              {formStatus.message && (
                <div className={`p-4 rounded-xl text-sm ${
                  formStatus.type === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' :
                  formStatus.type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
                  'bg-blue-50 text-blue-800 border border-blue-200'
                }`}>
                  {formStatus.message}
                </div>
              )}

              <button
                type="submit"
                disabled={formStatus.type === 'submitting'}
                className="w-full bg-primary text-white font-semibold py-3.5 rounded-xl hover:bg-accent disabled:bg-primary/50 transition-colors duration-200 flex items-center justify-center gap-2"
              >
                {formStatus.type === 'submitting' ? 'Sending...' : 'Send Message'} <ArrowRight size={16} />
              </button>
            </form>
          </div>
        </div>
      </section>

      {/* FOOTER */}
      <footer className="bg-foreground text-white py-16">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <div className="lg:col-span-2">
              <div className="flex items-center gap-3 mb-5">
                <div className="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                  <span className="text-white font-bold text-sm" style={{ fontFamily: "'Playfair Display', serif" }}>PB</span>
                </div>
                <div>
                  <div className="text-lg font-bold" style={{ fontFamily: "'Playfair Display', serif" }}>Prayogbharti Foundation</div>
                  <div className="text-xs text-white/40 tracking-widest uppercase">Est. 2004</div>
                </div>
              </div>
              <p className="text-white/60 text-sm leading-relaxed max-w-sm mb-6">
                A non-profit dedicated to transforming education for underprivileged individuals through scholarships, mentorship, and STEM programs across India.
              </p>
              <div className="flex gap-3">
                {["Facebook", "Twitter", "Instagram", "LinkedIn"].map((s) => (
                  <div key={s} className="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary transition-colors cursor-pointer">
                    <span className="text-white/60 text-xs">{s[0]}</span>
                  </div>
                ))}
              </div>
            </div>

            <div>
              <div className="text-white/40 text-xs font-bold uppercase tracking-widest mb-5">Quick Links</div>
              <div className="space-y-3">
                {NAV_LINKS.map((link) => (
                  <a key={link.label} href={link.href} className="block text-white/70 text-sm hover:text-primary transition-colors duration-200">
                    {link.label}
                  </a>
                ))}
              </div>
            </div>

            <div>
              <div className="text-white/40 text-xs font-bold uppercase tracking-widest mb-5">Our Programs</div>
              <div className="space-y-3">
                {["Scholarships", "STEM Coaching", "Mentorship", "Community Outreach", "Workshops", "Annual Reports"].map((p) => (
                  <a key={p} href="#programs" className="block text-white/70 text-sm hover:text-primary transition-colors duration-200">
                    {p}
                  </a>
                ))}
              </div>
            </div>
          </div>

          <div className="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p className="text-white/40 text-xs">
              © 2025 Prayogbharti Foundation. All rights reserved. Non-profit registered in India.
            </p>
            <div className="flex gap-6">
              {["Privacy Policy", "Terms of Use", "Donation Policy"].map((l) => (
                <a key={l} href="#" className="text-white/40 text-xs hover:text-primary transition-colors">
                  {l}
                </a>
              ))}
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
}
