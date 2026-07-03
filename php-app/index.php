<?php
/**
 * index.php – Main public-facing page
 * Prayogbharti Foundation – Digital Marketing Agency Website
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

/* ── Page Meta ─────────────────────────────────────────── */
$pageTitle       = 'Prayogbharti Foundation – Empowering Lives Through Education';
$metaDescription = 'Join us in making quality education accessible to all — especially in STEM fields for underprivileged individuals across India.';

/* ── Fetch Services from DB (with fallback) ─────────────── */
$services = [];
try {
    $stmt = db()->query("SELECT * FROM services WHERE active = 1 ORDER BY name ASC");
    $services = $stmt->fetchAll();
} catch (Exception $e) { /* silently fall through to fallback */ }

if (empty($services)) {
    $services = [
        ['name' => 'Scholarships',        'description' => 'Providing financial assistance for deserving students to pursue their education without barriers.', 'icon' => 'award',    'img_url' => 'assets/images/scholarships.jpg'],
        ['name' => 'Coaching Services',   'description' => 'Offering personalized coaching to enhance academic skills and performance in STEM disciplines.',    'icon' => 'book-open', 'img_url' => 'assets/images/coaching.jpg'],
        ['name' => 'Mentorship Programs', 'description' => 'Connecting students with experienced mentors for guidance, support, and career direction.',          'icon' => 'users',    'img_url' => 'assets/images/mentorship.jpg'],
        ['name' => 'Community Outreach',  'description' => 'Engaging with communities to promote educational opportunities and inspire local talent.',           'icon' => 'globe',    'img_url' => 'assets/images/community.jpg'],
    ];
}

/* ── Fetch Blog Posts from DB (with fallback) ───────────── */
$blogs = [];
try {
    $stmt = db()->query("
        SELECT bp.*, u.name AS author_name
        FROM blog_posts bp
        LEFT JOIN users u ON bp.author_id = u.id
        WHERE bp.status = 'published'
        ORDER BY bp.created_at DESC
        LIMIT 3
    ");
    $blogs = $stmt->fetchAll();
} catch (Exception $e) { /* silently fall through to fallback */ }

if (empty($blogs)) {
    $blogs = [
        ['title' => 'Breaking Barriers: How STEM Education Is Changing Rural India',       'summary' => 'Across villages in Maharashtra and Rajasthan, a quiet revolution is underway — one textbook at a time.', 'tags' => 'Education', 'created_at' => '2025-06-05', 'cover_image' => 'assets/images/blog1.jpg'],
        ['title' => 'Meet the Mentors: Professionals Who Give Back to the Community',      'summary' => 'From IIT graduates to doctors and engineers — the volunteers who spend weekends shaping young minds.',    'tags' => 'Mentorship', 'created_at' => '2025-05-18', 'cover_image' => 'assets/images/blog2.jpg'],
        ['title' => 'Scholarship Stories: The Faces Behind Our 2024 Annual Report',        'summary' => 'We sat down with five scholarship recipients to understand what financial support truly means.',            'tags' => 'Impact',     'created_at' => '2025-04-30', 'cover_image' => 'assets/images/blog3.jpg'],
    ];
}

/* ── Icon Map (Lucide) ─────────── */
$iconMap = [
    'Award'    => 'award',
    'BookOpen' => 'book-open',
    'Users'    => 'users',
    'Globe'    => 'globe',
    'Star'     => 'star',
    'Heart'    => 'heart',
    'marketing'=> 'bar-chart',
];

$defaultServiceImages = [
    'assets/images/scholarships.jpg',
    'assets/images/coaching.jpg',
    'assets/images/mentorship.jpg',
    'assets/images/community.jpg',
];

$defaultBlogImages = [
    "assets/images/blog1.jpg",
    "assets/images/blog2.jpg",
    "assets/images/blog3.jpg"
];

/* ── Static Data ──────────────────────────────────────────── */
$stats = [
    ['value' => '20+', 'label' => 'Years of Experience'],
    ['value' => '40+', 'label' => 'Team Members'],
    ['value' => '98%', 'label' => 'Satisfied Students'],
    ['value' => '500+','label' => 'Programs Completed'],
];

$whyUs = [
    ['num' => '01', 'title' => 'Inclusive Learning',       'desc' => 'We promote inclusivity by ensuring education is accessible to all, regardless of background, empowering every individual to achieve their potential.'],
    ['num' => '02', 'title' => 'Innovative Programs',      'desc' => 'Our programs embrace modern teaching methods and technologies to enhance learning experiences in STEM disciplines.'],
    ['num' => '03', 'title' => 'Community Impact',         'desc' => 'We foster local talent and contribute to the growth and development of the communities we serve.'],
    ['num' => '04', 'title' => 'Mentorship Opportunities', 'desc' => 'By connecting students with experienced mentors, we encourage personal and professional growth at every stage.'],
];

$testimonials = [
    ['name' => 'Anita Sharma', 'role' => 'Scholarship Recipient, 2022', 'text' => 'Prayogbharti Foundation has changed my life by providing educational opportunities I never thought possible. I am truly grateful for their support and guidance.', 'rating' => 5],
    ['name' => 'Raj Patel',    'role' => 'Mentorship Graduate, 2023',   'text' => 'The mentorship program opened doors I didn\'t know existed. Their guidance has been invaluable to my academic success and future career in engineering.', 'rating' => 5],
    ['name' => 'Priya Nair',   'role' => 'STEM Program Alumna',         'text' => 'Thanks to the coaching services, I cleared my competitive exams with confidence. The teachers here genuinely care about every student\'s growth.', 'rating' => 5],
];

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-background text-foreground" style="font-family: 'Nunito', sans-serif;">
  <?php include __DIR__ . '/includes/navbar.php'; ?>

  <!-- HERO -->
  <section id="home" class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('assets/images/hero-bg.jpg')"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0d3320]/90 via-[#0d3320]/65 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-10 pt-24 pb-16 grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <div class="inline-flex items-center gap-2 bg-primary/20 border border-primary/30 text-primary-foreground/90 text-xs font-semibold uppercase tracking-widest px-4 py-1.5 rounded-full mb-8" style="color: #F5A623">
          <i data-lucide="heart" fill="currentColor" width="12" height="12"></i>
          Non-Profit Education Initiative
        </div>
        <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.1] mb-6" style="font-family: 'Playfair Display', serif">
          Empowering
          <span class="block italic text-[#F5A623]">Lives Through</span>
          Education
        </h1>
        <p class="text-lg text-white/80 max-w-md mb-10 leading-relaxed">
          Join us in making quality education accessible to all — especially in STEM fields for underprivileged individuals across India.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="#contact" class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-accent transition-colors duration-200 text-sm">
            Get Involved <i data-lucide="arrow-right" width="16" height="16"></i>
          </a>
          <a href="#about" class="inline-flex items-center gap-2 border border-white/40 text-white font-semibold px-7 py-3.5 rounded-full hover:bg-white/10 transition-colors duration-200 text-sm">
            Our Mission
          </a>
        </div>

        <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-lg lg:max-w-none">
          <?php foreach ($stats as $s): ?>
            <div class="text-center lg:text-left">
              <div class="text-3xl font-bold text-[#F5A623]" style="font-family: 'Playfair Display', serif">
                <?= htmlspecialchars($s['value']) ?>
              </div>
              <div class="text-xs text-white/60 mt-1 leading-tight"><?= htmlspecialchars($s['label']) ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <a href="#about" class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/60 hover:text-white transition-colors animate-bounce">
      <i data-lucide="chevron-down" width="28" height="28"></i>
    </a>
  </section>

  <!-- ABOUT -->
  <section id="about" class="py-24 bg-background">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16 items-center">
      <div class="relative">
        <div class="rounded-2xl overflow-hidden aspect-[4/5] bg-muted">
          <img src="assets/images/about-img.jpg" alt="Students learning together" class="w-full h-full object-cover" />
        </div>
        <div class="absolute -bottom-6 -right-6 bg-primary text-white rounded-2xl p-6 shadow-xl max-w-[200px]">
          <div class="text-4xl font-bold" style="font-family: 'Playfair Display', serif">20+</div>
          <div class="text-sm text-white/80 mt-1">Years transforming lives through education</div>
        </div>
        <div class="absolute top-6 -left-6 bg-[#F5A623] rounded-2xl p-4 shadow-xl">
          <i data-lucide="heart" class="text-white" fill="white" width="28" height="28"></i>
        </div>
      </div>

      <div>
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">Our Mission</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground mb-6 leading-[1.15]" style="font-family: 'Playfair Display', serif">
          The Inspiring Journey Behind Prayogbharti Foundation
        </h2>
        <p class="text-muted-foreground text-lg mb-6 leading-relaxed">
          Founded to inspire change, our organization harnesses the power of education to uplift underprivileged individuals and create opportunities for a better future. We believe that every child, regardless of economic background, deserves access to quality learning.
        </p>
        <p class="text-muted-foreground leading-relaxed mb-10">
          Over two decades, we have built a network of educators, mentors, and volunteers who share a common vision — a society where talent and hard work determine one's future, not the circumstances of birth.
        </p>
        <a href="#programs" class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-accent transition-colors duration-200 text-sm">
          Explore Programs <i data-lucide="arrow-right" width="16" height="16"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section id="services" class="py-24 bg-secondary">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="text-center mb-16">
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">Our Services</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground mb-4" style="font-family: 'Playfair Display', serif">
          Comprehensive Educational
          <span class="block italic">Support Programs</span>
        </h2>
        <p class="text-muted-foreground max-w-xl mx-auto text-lg">
          Programs including scholarships, mentorship, coaching, and resources designed to empower students of all ages.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($services as $index => $s): 
          $iconName = $iconMap[$s['icon']] ?? $s['icon'] ?? 'award';
          $imgUrl = !empty($s['img_url']) ? $s['img_url'] : $defaultServiceImages[$index % count($defaultServiceImages)];
        ?>
          <div class="bg-card rounded-2xl overflow-hidden group hover:shadow-lg transition-shadow duration-300 border border-border">
            <div class="relative h-48 bg-muted overflow-hidden">
              <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($s['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div class="absolute inset-0 bg-gradient-to-t from-foreground/40 to-transparent"></div>
              <div class="absolute bottom-4 left-4 bg-primary rounded-xl p-2">
                <i data-lucide="<?= htmlspecialchars($iconName) ?>" class="text-white" width="20" height="20"></i>
              </div>
            </div>
            <div class="p-5">
              <h3 class="text-lg font-bold text-foreground mb-2" style="font-family: 'Playfair Display', serif">
                <?= htmlspecialchars($s['name']) ?>
              </h3>
              <p class="text-muted-foreground text-sm leading-relaxed"><?= htmlspecialchars($s['description']) ?></p>
              <button class="mt-4 text-primary text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all duration-200">
                Read More <i data-lucide="arrow-right" width="14" height="14"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PROGRAMS / WHY CHOOSE US -->
  <section id="programs" class="py-24 bg-background">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16 items-center">
      <div>
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">Why Choose Us</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground mb-4 leading-[1.15]" style="font-family: 'Playfair Display', serif">
          Commitment to
          <span class="block italic text-primary">Excellence</span>
        </h2>
        <p class="text-muted-foreground text-lg mb-10 leading-relaxed">
          We don't just provide education — we build futures. Each program is thoughtfully designed to address real barriers and create lasting impact.
        </p>

        <div class="space-y-6">
          <?php foreach ($whyUs as $item): ?>
            <div class="flex gap-5 group">
              <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm group-hover:bg-primary group-hover:text-white transition-colors duration-300" style="font-family: 'DM Mono', monospace">
                <?= htmlspecialchars($item['num']) ?>
              </div>
              <div>
                <h4 class="font-bold text-foreground mb-1" style="font-family: 'Playfair Display', serif">
                  <?= htmlspecialchars($item['title']) ?>
                </h4>
                <p class="text-muted-foreground text-sm leading-relaxed"><?= htmlspecialchars($item['desc']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="relative">
        <div class="rounded-2xl overflow-hidden aspect-square bg-muted">
          <img src="assets/images/whyus-img.jpg" alt="Diverse group of students and mentors" class="w-full h-full object-cover" />
        </div>
        <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-border"></div>
        <div class="absolute -bottom-8 left-8 right-8 bg-white rounded-2xl p-5 shadow-xl border border-border flex items-center gap-4">
          <div class="w-12 h-12 bg-[#F5A623] rounded-full flex items-center justify-center flex-shrink-0">
            <i data-lucide="star" class="text-white" fill="white" width="20" height="20"></i>
          </div>
          <div>
            <div class="text-foreground font-bold text-sm">4.8 Google Rating</div>
            <div class="flex gap-0.5 mt-1">
              <?php for ($i=0; $i<5; $i++): ?>
                <i data-lucide="star" class="text-[#F5A623]" fill="#F5A623" width="12" height="12"></i>
              <?php endfor; ?>
            </div>
            <div class="text-muted-foreground text-xs mt-0.5">from 2,000+ happy students</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- IMPACT STRIP -->
  <section id="impact" class="py-20 bg-primary">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($stats as $s): ?>
          <div class="text-center">
            <div class="text-5xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif">
              <?= htmlspecialchars($s['value']) ?>
            </div>
            <div class="text-white/70 text-sm tracking-wide"><?= htmlspecialchars($s['label']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="py-24 bg-secondary">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="text-center mb-16">
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">What They Say</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground" style="font-family: 'Playfair Display', serif">
          Voices from Our
          <span class="block italic">Community</span>
        </h2>
      </div>

      <div class="grid lg:grid-cols-3 gap-6" id="testimonial-container">
        <?php foreach ($testimonials as $i => $t): ?>
          <div class="bg-card border border-border rounded-2xl p-7 transition-shadow duration-300 <?= $i === 0 ? 'shadow-lg ring-2 ring-primary/20' : 'hover:shadow-md' ?> testimonial-card cursor-pointer">
            <i data-lucide="quote" class="text-primary/30 mb-4" width="28" height="28"></i>
            <p class="text-foreground/80 leading-relaxed mb-6 text-sm"><?= htmlspecialchars($t['text']) ?></p>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                <?= htmlspecialchars(substr($t['name'], 0, 1)) ?>
              </div>
              <div>
                <div class="font-bold text-foreground text-sm"><?= htmlspecialchars($t['name']) ?></div>
                <div class="text-muted-foreground text-xs"><?= htmlspecialchars($t['role']) ?></div>
              </div>
              <div class="ml-auto flex gap-0.5">
                <?php for ($j=0; $j<$t['rating']; $j++): ?>
                  <i data-lucide="star" class="text-[#F5A623]" fill="#F5A623" width="12" height="12"></i>
                <?php endfor; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- BLOG -->
  <section id="blog" class="py-24 bg-background">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-14 gap-4">
        <div>
          <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">Latest News</div>
          <h2 class="text-4xl font-bold text-foreground" style="font-family: 'Playfair Display', serif">
            Stories of
            <span class="italic"> Impact</span>
          </h2>
        </div>
        <a href="#blog" class="inline-flex items-center gap-2 text-primary font-semibold text-sm hover:gap-3 transition-all duration-200">
          View all posts <i data-lucide="arrow-right" width="14" height="14"></i>
        </a>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <?php foreach ($blogs as $index => $post): 
          $coverImg = !empty($post['cover_image']) ? $post['cover_image'] : $defaultBlogImages[$index % count($defaultBlogImages)];
          $category = !empty($post['tags']) ? explode(',', $post['tags'])[0] : 'General';
          $formattedDate = date("F j, Y", strtotime($post['created_at']));
        ?>
          <article class="group bg-card border border-border rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative h-52 bg-muted overflow-hidden">
              <img src="<?= htmlspecialchars($coverImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <span class="absolute top-4 left-4 bg-primary text-white text-xs font-bold uppercase tracking-wide px-3 py-1 rounded-full">
                <?= htmlspecialchars($category) ?>
              </span>
            </div>
            <div class="p-6">
              <div class="text-muted-foreground text-xs mb-3" style="font-family: 'DM Mono', monospace">
                <?= htmlspecialchars($formattedDate) ?>
              </div>
              <h3 class="text-lg font-bold text-foreground mb-3 leading-snug group-hover:text-primary transition-colors duration-200" style="font-family: 'Playfair Display', serif">
                <?= htmlspecialchars($post['title']) ?>
              </h3>
              <p class="text-muted-foreground text-sm leading-relaxed"><?= htmlspecialchars($post['summary']) ?></p>
              <button class="mt-5 text-primary text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all duration-200">
                Read more <i data-lucide="arrow-right" width="14" height="14"></i>
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-24 bg-foreground relative overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-10" style="background-image: url('assets/images/cta-bg.jpg')"></div>
    <div class="relative max-w-4xl mx-auto px-6 lg:px-10 text-center">
      <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-8">
        <i data-lucide="heart" class="text-white" fill="white" width="28" height="28"></i>
      </div>
      <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6" style="font-family: 'Playfair Display', serif">
        Together, We Can Make
        <span class="block italic text-[#F5A623]">a Difference</span>
      </h2>
      <p class="text-white/70 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
        Your contribution can help provide scholarships and mentorship to deserving students, empowering them for a brighter future. Every donation counts.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-8 py-4 rounded-full hover:bg-accent transition-colors duration-200">
          Donate Now <i data-lucide="heart" fill="currentColor" width="16" height="16"></i>
        </a>
        <a href="#programs" class="inline-flex items-center gap-2 border border-white/30 text-white font-semibold px-8 py-4 rounded-full hover:bg-white/10 transition-colors duration-200">
          Volunteer With Us
        </a>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="py-24 bg-background">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16">
      <div>
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">Get In Touch</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground mb-6" style="font-family: 'Playfair Display', serif">
          Let's Start a
          <span class="block italic">Conversation</span>
        </h2>
        <p class="text-muted-foreground text-lg mb-10 leading-relaxed">
          Whether you want to donate, volunteer, apply for a scholarship, or simply learn more about our work — we'd love to hear from you.
        </p>
        <div class="space-y-5">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <i data-lucide="mail" class="text-primary" width="18" height="18"></i>
            </div>
            <div>
              <div class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-0.5">Email Us</div>
              <div class="text-foreground font-medium">contact@prayogbharti.org</div>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <i data-lucide="phone" class="text-primary" width="18" height="18"></i>
            </div>
            <div>
              <div class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-0.5">Call Us</div>
              <div class="text-foreground font-medium">+91 98765 43210</div>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <i data-lucide="map-pin" class="text-primary" width="18" height="18"></i>
            </div>
            <div>
              <div class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-0.5">Location</div>
              <div class="text-foreground font-medium">India (Serving communities nationwide)</div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-card border border-border rounded-2xl p-8">
        <h3 class="text-2xl font-bold text-foreground mb-6" style="font-family: 'Playfair Display', serif">
          Send Us a Message
        </h3>
        <form class="space-y-5" id="contact-form">
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-foreground mb-2">First Name *</label>
              <input type="text" name="firstName" placeholder="Anita" required class="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-foreground mb-2">Last Name</label>
              <input type="text" name="lastName" placeholder="Sharma" class="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-foreground mb-2">Email Address *</label>
            <input type="email" name="email" placeholder="anita@example.com" required class="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all" />
          </div>
          <div>
            <label class="block text-sm font-semibold text-foreground mb-2">I am interested in…</label>
            <select name="interest" class="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all">
              <option value="Donating / Sponsoring">Donating / Sponsoring</option>
              <option value="Volunteering / Mentoring">Volunteering / Mentoring</option>
              <option value="Applying for Scholarship">Applying for Scholarship</option>
              <option value="Partnership / Collaboration">Partnership / Collaboration</option>
              <option value="General Enquiry">General Enquiry</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-foreground mb-2">Message *</label>
            <textarea name="message" rows="4" placeholder="Tell us a little about yourself and how you'd like to get involved…" required class="w-full bg-input-background border border-border rounded-xl px-4 py-3 text-foreground placeholder-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all resize-none"></textarea>
          </div>

          <div id="form-status" class="p-4 rounded-xl text-sm hidden"></div>

          <button type="submit" id="submit-btn" class="w-full bg-primary text-white font-semibold py-3.5 rounded-xl hover:bg-accent disabled:bg-primary/50 transition-colors duration-200 flex items-center justify-center gap-2">
            Send Message <i data-lucide="arrow-right" width="16" height="16"></i>
          </button>
        </form>
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</div>

<script>
// Testimonial interaction script
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.testimonial-card');
  cards.forEach(card => {
    card.addEventListener('click', () => {
      cards.forEach(c => {
        c.classList.remove('shadow-lg', 'ring-2', 'ring-primary/20');
        c.classList.add('hover:shadow-md');
      });
      card.classList.remove('hover:shadow-md');
      card.classList.add('shadow-lg', 'ring-2', 'ring-primary/20');
    });
  });
});
</script>
