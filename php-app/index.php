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
    
    // Auto-update DB if it only contains the 4 old default services
    if (count($services) <= 4) {
        $hasOldOnly = true;
        foreach ($services as $s) {
            if (!in_array($s['name'], ['Scholarships', 'Coaching Services', 'Mentorship Programs', 'Community Outreach'])) {
                $hasOldOnly = false;
                break;
            }
        }
        if ($hasOldOnly) {
            db()->exec("DELETE FROM services");
            $newServices = [
                ['Research & Development', 'Conducting research projects to explore cutting-edge technologies in education and address challenges faced by students and educators.', 'microscope'],
                ['Mentorship', 'Connecting students with experienced professionals, coding mentors, and educators for guidance, skills, and personal growth.', 'users'],
                ['Hackathons', 'Organizing technology competitions and hackathons to foster innovation, collaborative coding, and problem-solving.', 'trophy'],
                ['Workshops & Events', 'Conducting interactive workshops and events on digital literacy, technology trends, and modern learning practices.', 'calendar'],
                ['Corporate Solutions', 'Providing tailored technical services, consulting, and solutions for organizations and corporate partners.', 'briefcase'],
                ['Entrepreneurship', 'Supporting individuals in tech startup development, innovation coaching, and entrepreneurial skill building.', 'lightbulb'],
                ['Skill Empowerment', 'Providing students with tools, guidance, resources, and coaching required for academic and professional growth.', 'award'],
                ['Career Counselling', 'Assisting individuals in making informed decisions about their careers, skills development, and job market trends.', 'compass'],
                ['Latest Technology Training', 'Offering coaching and hands-on training in coding, software development, and new technology fields.', 'cpu'],
                ['Live Industry Projects', 'Engaging students in practical, real-world development projects to build industry-relevant experience.', 'git-branch'],
                ['Cyber Security Internship', 'Hands-on internship programs covering Information Security, Application Security, Cloud Security, and DevSecOps.', 'shield'],
                ['Application & Website Development', 'Fostering development skills through designing, building, and deploying real-world software applications and websites.', 'layout'],
                ['Digital Marketing Internship', 'Practical training in digital marketing strategies, campaign management, and digital landscape navigation.', 'trending-up']
            ];
            $insStmt = db()->prepare("INSERT INTO services (name, description, icon, active) VALUES (?, ?, ?, 1)");
            foreach ($newServices as $ns) {
                $insStmt->execute($ns);
            }
            $stmt = db()->query("SELECT * FROM services WHERE active = 1 ORDER BY name ASC");
            $services = $stmt->fetchAll();
        }
    }
} catch (Exception $e) { /* silently fall through to fallback */ }

if (empty($services)) {
    $services = [
        ['name' => 'Research & Development', 'description' => 'Conducting research projects to explore cutting-edge technologies in education and address challenges faced by students and educators.', 'icon' => 'microscope', 'img_url' => 'assets/images/scholarships.jpg'],
        ['name' => 'Mentorship', 'description' => 'Connecting students with experienced professionals, coding mentors, and educators for guidance, skills, and personal growth.', 'icon' => 'users', 'img_url' => 'assets/images/mentorship.jpg'],
        ['name' => 'Hackathons', 'description' => 'Organizing technology competitions and hackathons to foster innovation, collaborative coding, and problem-solving.', 'icon' => 'trophy', 'img_url' => 'assets/images/coaching.jpg'],
        ['name' => 'Workshops & Events', 'description' => 'Conducting interactive workshops and events on digital literacy, technology trends, and modern learning practices.', 'icon' => 'calendar', 'img_url' => 'assets/images/community.jpg'],
        ['name' => 'Corporate Solutions', 'description' => 'Providing tailored technical services, consulting, and solutions for organizations and corporate partners.', 'icon' => 'briefcase', 'img_url' => 'assets/images/scholarships.jpg'],
        ['name' => 'Entrepreneurship', 'description' => 'Supporting individuals in tech startup development, innovation coaching, and entrepreneurial skill building.', 'icon' => 'lightbulb', 'img_url' => 'assets/images/mentorship.jpg'],
        ['name' => 'Skill Empowerment', 'description' => 'Providing students with tools, guidance, resources, and coaching required for academic and professional growth.', 'icon' => 'award', 'img_url' => 'assets/images/coaching.jpg'],
        ['name' => 'Career Counselling', 'description' => 'Assisting individuals in making informed decisions about their careers, skills development, and job market trends.', 'icon' => 'compass', 'img_url' => 'assets/images/community.jpg'],
        ['name' => 'Latest Technology Training', 'description' => 'Offering coaching and hands-on training in coding, software development, and new technology fields.', 'icon' => 'cpu', 'img_url' => 'assets/images/scholarships.jpg'],
        ['name' => 'Live Industry Projects', 'description' => 'Engaging students in practical, real-world development projects to build industry-relevant experience.', 'icon' => 'git-branch', 'img_url' => 'assets/images/mentorship.jpg'],
        ['name' => 'Cyber Security Internship', 'description' => 'Hands-on internship programs covering Information Security, Application Security, Cloud Security, and DevSecOps.', 'icon' => 'shield', 'img_url' => 'assets/images/coaching.jpg'],
        ['name' => 'Application & Website Development', 'description' => 'Fostering development skills through designing, building, and deploying real-world software applications and websites.', 'icon' => 'layout', 'img_url' => 'assets/images/community.jpg'],
        ['name' => 'Digital Marketing Internship', 'description' => 'Practical training in digital marketing strategies, campaign management, and digital landscape navigation.', 'icon' => 'trending-up', 'img_url' => 'assets/images/scholarships.jpg']
    ];
}

/* ── Fetch Blog Posts from DB (with fallback) ───────────── */
$blogs = [];
try {
    // Auto-update blogs in DB if they are the old ones
    $stmt = db()->query("SELECT id, title FROM blog_posts");
    $existingBlogs = $stmt->fetchAll();
    if (count($existingBlogs) <= 3) {
        $hasOldOnly = true;
        foreach ($existingBlogs as $eb) {
            if (!in_array($eb['title'], [
                'Breaking Barriers: How STEM Education Is Changing Rural India',
                'Meet the Mentors: Professionals Who Give Back to the Community',
                'Scholarship Stories: The Faces Behind Our 2024 Annual Report'
            ])) {
                $hasOldOnly = false;
                break;
            }
        }
        if ($hasOldOnly) {
            db()->exec("DELETE FROM blog_posts");
            $newBlogs = [
                [
                    'Innovation in Education through Technology',
                    'innovation-education-through-technology',
                    'Exploring how cutting-edge technology, EdTech solutions, and digital literacy initiatives are transforming learning environments and fostering innovation.',
                    '<p class="mb-6">At Prayogbharti Foundation, we are committed to fostering positive change through research and development initiatives. Technology is at the heart of this transformation, playing a key role in empowering individuals across different educational levels.</p><p class="mb-6">Our research projects explore the integration of cutting-edge technologies in educational settings, enhancing teaching methods and learning experiences. By designing interactive and inclusive environments, we help educators and students connect in more meaningful ways.</p><h3 class="text-2xl font-bold mb-4 mt-8" style="font-family: \'Playfair Display\', serif;">Specific R&D Initiatives</h3><ul class="list-disc pl-6 mb-6 space-y-2"><li><strong>Innovation for Education:</strong> Researching new pedagogical tools and technologies to make classrooms more engaging.</li><li><strong>EdTech Solutions:</strong> Developing applications and platforms tailored to meet local educational challenges.</li><li><strong>Digital Literacy:</strong> Equipping students and teachers with the skills necessary to safely and effectively navigate the digital world.</li></ul><p class="mb-6">Through these initiatives, our goal is to provide students and communities with the tools, guidance, resources, and opportunities required for personal, academic, and professional growth.</p>',
                    1, 'assets/images/blog1.jpg', 'Innovation', 'published'
                ],
                [
                    'Expanding Access through STEM Scholarships',
                    'expanding-access-through-stem-scholarships',
                    'How providing financial support, merit-based assistance, and tech access empowers underrepresented students in science and engineering fields.',
                    '<p class="mb-6">Education is a fundamental right, yet many deserving students face financial and structural barriers that prevent them from pursuing their dreams. Prayogbharti Foundation’s Scholarship Programs are designed to bridge this gap, ensuring that talent alone determines a student’s future.</p><p class="mb-6">We provide dedicated STEM scholarships, financial access, and tech access for students from economically disadvantaged and underrepresented backgrounds. This support goes beyond financial assistance; it provides students with the technology resources they need to thrive in a digital-first economy.</p><h3 class="text-2xl font-bold mb-4 mt-8" style="font-family: \'Playfair Display\', serif;">Key Scholarship Features</h3><ul class="list-disc pl-6 mb-6 space-y-2"><li><strong>STEM Scholarships:</strong> Focused on Science, Technology, Engineering, and Mathematics disciplines.</li><li><strong>Tech & Financial Access:</strong> Providing laptops, internet access, and tuition fees to eliminate learning barriers.</li><li><strong>Inclusivity & Merit Support:</strong> Recognizing academic excellence while promoting opportunities for underrepresented communities.</li></ul><p class="mb-6">By investing in these future leaders, we are not only supporting individual academic journeys but also contributing to the advancement of society as a whole.</p>',
                    1, 'assets/images/blog2.jpg', 'Scholarships', 'published'
                ],
                [
                    'Mentorship and Career Guidance for Future Leaders',
                    'mentorship-career-guidance-future-leaders',
                    'Connecting aspiring students with experienced technology professionals and educators to build career pathways and key life skills.',
                    '<p class="mb-6">Knowledge is powerful, but guidance is the compass that points it in the right direction. Through our Mentorship Programs, we connect students with tech professionals, developers, educators, and community leaders who volunteer their time and expertise.</p><p class="mb-6">Mentees receive career guidance, hands-on skills training, and personal development coaching. By establishing strong mentor-mentee networks, we prepare individuals for future opportunities and inspire them to become active contributors to their communities.</p><h3 class="text-2xl font-bold mb-4 mt-8" style="font-family: \'Playfair Display\', serif;">Mentorship Highlights</h3><ul class="list-disc pl-6 mb-6 space-y-2"><li><strong>Tech Mentor Networks:</strong> Direct interaction with industry professionals from top technology sectors.</li><li><strong>Practical Coding Mentors:</strong> Hands-on coaching in software development and technical project management.</li><li><strong>Personal Development:</strong> Seminars and workshops focusing on communication, confidence, and leadership skills.</li></ul><p class="mb-6">Our structured mentorship pathways help transition students from academic environments into industry-ready contributors, paving the way for sustainable career success.</p>',
                    1, 'assets/images/blog3.jpg', 'Mentorship', 'published'
                ]
            ];
            $insStmt = db()->prepare("INSERT INTO blog_posts (title, slug, summary, content, author_id, cover_image, tags, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($newBlogs as $nb) {
                $insStmt->execute($nb);
            }
        }
    }

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
        [
            'id' => 1,
            'title' => 'Innovation in Education through Technology',
            'summary' => 'Exploring how cutting-edge technology, EdTech solutions, and digital literacy initiatives are transforming learning environments and fostering innovation.',
            'tags' => 'Innovation',
            'created_at' => '2026-07-10',
            'cover_image' => 'assets/images/blog1.jpg'
        ],
        [
            'id' => 2,
            'title' => 'Expanding Access through STEM Scholarships',
            'summary' => 'How providing financial support, merit-based assistance, and tech access empowers underrepresented students in science and engineering fields.',
            'tags' => 'Scholarships',
            'created_at' => '2026-07-05',
            'cover_image' => 'assets/images/blog2.jpg'
        ],
        [
            'id' => 3,
            'title' => 'Mentorship and Career Guidance for Future Leaders',
            'summary' => 'Connecting aspiring students with experienced technology professionals and educators to build career pathways and key life skills.',
            'tags' => 'Mentorship',
            'created_at' => '2026-06-28',
            'cover_image' => 'assets/images/blog3.jpg'
        ]
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

$coreValues = [
    ['title' => 'Tech Inclusivity', 'desc' => 'Ensure that technology education is inclusive, striving to bridge the digital divide and providing equal opportunities for all.', 'icon' => 'laptop'],
    ['title' => 'Innovation Excellence', 'desc' => 'Foster a culture of innovation, encouraging individuals to explore, create, and contribute to technological advancements.', 'icon' => 'lightbulb'],
    ['title' => 'Ethical Technology Practices', 'desc' => 'Promote ethical considerations in technology, emphasizing responsible use and the positive societal impacts that technology can bring.', 'icon' => 'shield-check'],
    ['title' => 'Collaboration', 'desc' => 'Encourage collaboration among students, educators, industry professionals, and communities to build a collective vision for a technologically empowered future.', 'icon' => 'users'],
    ['title' => 'Equality & Inclusivity', 'desc' => 'Ensure that educational opportunities are accessible to all, regardless of socio-economic background or other barriers.', 'icon' => 'scale'],
    ['title' => 'Excellence', 'desc' => 'Strive for excellence in research, scholarship programs, mentorship, and coaching, aiming for positive and lasting impacts.', 'icon' => 'award'],
    ['title' => 'Community Engagement', 'desc' => 'Foster a sense of community and collaboration among students, educators, mentors, and the broader community.', 'icon' => 'globe'],
    ['title' => 'Empowerment', 'desc' => 'Empower individuals to reach their full potential by providing the necessary support and resources.', 'icon' => 'heart'],
];

$impactAreas = [
    ['title' => 'Digital Literacy', 'desc' => 'Equipping students, educators, and communities with essential tools to navigate the digital landscape effectively.', 'icon' => 'laptop'],
    ['title' => 'Educational Innovation', 'desc' => 'Integrating cutting-edge technologies and developing effective teaching methods to enhance learning.', 'icon' => 'lightbulb'],
    ['title' => 'Scholarship Support', 'desc' => 'Providing STEM scholarships, financial assistance, and inclusive opportunities for deserving students.', 'icon' => 'award'],
    ['title' => 'Career & Mentorship Guidance', 'desc' => 'Connecting individuals with industry professionals and leaders to guide their academic and career journeys.', 'icon' => 'users'],
    ['title' => 'Community Development', 'desc' => 'Fostering local talent and implementing educational, social, and developmental projects for community welfare.', 'icon' => 'globe'],
    ['title' => 'Public Health Awareness', 'desc' => 'Creating awareness and conducting programs on public health, sanitation, and medical relief for the underprivileged.', 'icon' => 'activity'],
    ['title' => 'Environment Protection', 'desc' => 'Organizing community awareness movements and programs dedicated to protecting the environment.', 'icon' => 'leaf'],
    ['title' => 'Social Welfare', 'desc' => 'Providing relief and support to the poor and downtrodden to promote equality and social well-being.', 'icon' => 'heart'],
];

$products = [
    [
        'title' => 'Research & Development Hub',
        'desc' => 'Conducting core research projects to address challenges in society, education, and digital settings.',
        'icon' => 'flask-conical',
        'items' => ['Research & Development', 'Mentorship', 'Hackathons', 'Workshops & Events', 'Corporate Solutions', 'Entrepreneurship']
    ],
    [
        'title' => 'Skill Empowerment Platform',
        'desc' => 'Providing individuals with essential coaching, training, and resources to prepare for career growth.',
        'icon' => 'sparkles',
        'items' => ['Skill Empowerment', 'Counselling', 'Latest Technology Training', 'Live Industry Projects', 'Industry-Ready Resources', 'Corporate Projects']
    ],
    [
        'title' => 'Prayogbharti Alumni Network',
        'desc' => 'A community of graduates collaborating, mentoring, and staying updated with industry trends.',
        'icon' => 'graduation-cap',
        'items' => ['Prayogbharti Alumni', 'Latest Technology Trends', 'Career Counselling', 'Job Enrichment', 'Networking Opportunities']
    ],
    [
        'title' => 'Research Incubation Centre',
        'desc' => 'Fostering hands-on practical research, internships, and product creation in cutting-edge tech.',
        'icon' => 'rocket',
        'items' => [
            'Research Incubation Centre',
            'Product Research',
            'Cyber Security Internship (Information Security, Application Security, Cloud Security, DevSecOps)',
            'Development Internship (Application & Website Development)',
            'Digital Marketing Internship'
        ]
    ]
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
    ['num' => '01', 'title' => 'Research & Development',      'desc' => 'Promoting educational innovation, EdTech solutions, digital literacy, effective learning practices, and research addressing challenges in education and society.'],
    ['num' => '02', 'title' => 'Scholarship Programs',     'desc' => 'Providing STEM scholarships, financial assistance, merit-based support, technology access, and inclusive opportunities for deserving students.'],
    ['num' => '03', 'title' => 'Mentorship Programs',      'desc' => 'Connecting students with technology professionals, coding mentors, educators, and community leaders for career guidance, practical skills, and personal development.'],
    ['num' => '04', 'title' => 'Coaching & Skill Development', 'desc' => 'Offering coding, technology, entrepreneurship, academic, leadership, and career coaching to prepare individuals for future opportunities.'],
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
          We Are Committed to Fostering
          <span class="block italic text-[#F5A623]">Positive Change</span>
          through Research and Development Initiatives
        </h1>
        <p class="text-lg text-white/80 max-w-md mb-10 leading-relaxed">
          Prayogbharti Foundation empowers individuals across different educational levels through research and development initiatives, scholarship programs, mentorship, coaching, technology, innovation, and skill development.
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
          Empowering Education Through Innovation
        </h2>
        <p class="text-muted-foreground text-lg mb-6 leading-relaxed">
          We are committed to fostering positive change through research and development initiatives, scholarship programs, mentorship programs, and coaching services. Our goal is to provide students and communities with the tools, guidance, resources, and opportunities required for personal, academic, and professional growth.
        </p>
        <div class="bg-primary/5 border-l-4 border-primary p-5 rounded-r-xl mb-10">
          <div class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Our Mission</div>
          <p class="text-muted-foreground italic leading-relaxed text-sm">
            "To empower individuals through inclusive education, technology, research, mentorship, coaching, and community-focused development."
          </p>
        </div>
        <a href="#programs" class="inline-flex items-center gap-2 bg-primary text-white font-semibold px-7 py-3.5 rounded-full hover:bg-accent transition-colors duration-200 text-sm">
          Explore Programs <i data-lucide="arrow-right" width="16" height="16"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- CORE VALUES -->
  <section id="values" class="py-24 bg-secondary">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="text-center mb-16">
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4 font-semibold">How We Operate</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground mb-4" style="font-family: 'Playfair Display', serif">
          Our Core Values
        </h2>
        <p class="text-muted-foreground max-w-xl mx-auto text-lg">
          The foundational principles guiding our organization's efforts to foster positive change and empower education.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($coreValues as $value): ?>
          <div class="bg-card rounded-2xl p-6 border border-border flex flex-col justify-between group hover:shadow-lg transition-all duration-300">
            <div>
              <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center mb-5 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                <i data-lucide="<?= htmlspecialchars($value['icon']) ?>" width="24" height="24"></i>
              </div>
              <h3 class="text-lg font-bold text-foreground mb-3" style="font-family: 'Playfair Display', serif">
                <?= htmlspecialchars($value['title']) ?>
              </h3>
              <p class="text-muted-foreground text-sm leading-relaxed"><?= htmlspecialchars($value['desc']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section id="services" class="py-24 bg-background">
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
          <div class="bg-card rounded-2xl overflow-hidden group hover:shadow-lg transition-shadow duration-300 border border-border flex flex-col justify-between">
            <div>
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
              </div>
            </div>
            <div class="px-5 pb-5">
              <button
                class="service-read-more text-primary text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all duration-200"
                data-service-id="<?= $s['id'] ?? $index ?>"
                data-title="<?= htmlspecialchars($s['name'], ENT_QUOTES) ?>"
                data-description="<?= htmlspecialchars($s['description'], ENT_QUOTES) ?>"
                data-name="<?= htmlspecialchars($s['name'], ENT_QUOTES) ?>"
                data-desc="<?= htmlspecialchars($s['description'], ENT_QUOTES) ?>"
                data-icon="<?= htmlspecialchars($iconName, ENT_QUOTES) ?>"
                type="button">
                Read More
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round"
                     style="pointer-events:none; flex-shrink:0;">
                  <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PROGRAMS / WHY CHOOSE US -->
  <section id="programs" class="py-24 bg-secondary">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 grid lg:grid-cols-2 gap-16 items-center">
      <div>
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4">Programs</div>
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

  <!-- IMPACT -->
  <section id="impact" class="py-24 bg-primary text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <!-- Original Stats Strip -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-16 border-b border-white/20">
        <?php foreach ($stats as $s): ?>
          <div class="text-center">
            <div class="text-5xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif">
              <?= htmlspecialchars($s['value']) ?>
            </div>
            <div class="text-white/70 text-sm tracking-wide"><?= htmlspecialchars($s['label']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- New Impact Areas Section -->
      <div class="mt-16">
        <div class="text-center mb-12">
          <div class="text-accent text-sm font-bold uppercase tracking-widest mb-4 font-semibold" style="color: #F5A623">Sectors of Action</div>
          <h2 class="text-4xl font-bold text-white mb-4" style="font-family: 'Playfair Display', serif">
            Driving Positive Change
          </h2>
          <p class="text-white/70 max-w-xl mx-auto text-base">
            We regularly evaluate the impact of our programs to adapt and meet the evolving needs of communities nationwide.
          </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <?php foreach ($impactAreas as $area): ?>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/25 flex flex-col justify-between group hover:bg-white/15 transition-all duration-300">
              <div>
                <div class="w-12 h-12 bg-white/20 text-[#F5A623] rounded-xl flex items-center justify-center mb-5">
                  <i data-lucide="<?= htmlspecialchars($area['icon']) ?>" width="24" height="24"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-3" style="font-family: 'Playfair Display', serif">
                  <?= htmlspecialchars($area['title']) ?>
                </h3>
                <p class="text-white/80 text-sm leading-relaxed"><?= htmlspecialchars($area['desc']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- PRODUCTS & INITIATIVES -->
  <section id="products" class="py-24 bg-background">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
      <div class="text-center mb-16">
        <div class="text-primary text-sm font-bold uppercase tracking-widest mb-4 font-semibold">Our Offerings</div>
        <h2 class="text-4xl lg:text-5xl font-bold text-foreground mb-4" style="font-family: 'Playfair Display', serif">
          Products & Initiatives
        </h2>
        <p class="text-muted-foreground max-w-xl mx-auto text-lg">
          A comprehensive list of initiatives, internship programs, alumni networks, and development platforms.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($products as $p): ?>
          <div class="bg-card rounded-2xl p-6 border border-border flex flex-col justify-between group hover:shadow-lg transition-all duration-300">
            <div>
              <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center mb-5 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                <i data-lucide="<?= htmlspecialchars($p['icon']) ?>" width="24" height="24"></i>
              </div>
              <h3 class="text-xl font-bold text-foreground mb-3" style="font-family: 'Playfair Display', serif">
                <?= htmlspecialchars($p['title']) ?>
              </h3>
              <p class="text-muted-foreground text-sm mb-6"><?= htmlspecialchars($p['desc']) ?></p>
              
              <ul class="space-y-2">
                <?php foreach ($p['items'] as $item): ?>
                  <li class="flex items-start gap-2 text-xs text-muted-foreground">
                    <i data-lucide="check-circle-2" class="text-primary mt-0.5 flex-shrink-0" width="14" height="14"></i>
                    <span><?= htmlspecialchars($item) ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
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
          $postId = $post['id'] ?? ($index + 1);
        ?>
          <article class="group bg-card border border-border rounded-2xl overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col justify-between">
            <div>
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
              </div>
            </div>
            <div class="px-6 pb-6">
              <a href="blog-details.php?id=<?= $postId ?>" class="text-primary text-sm font-semibold inline-flex items-center gap-1 hover:gap-2 transition-all duration-200">
                Read more <i data-lucide="arrow-right" width="14" height="14"></i>
              </a>
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

  <!-- NEWSLETTER -->
  <section class="newsletter-section">
    <div class="newsletter-inner max-w-7xl mx-auto px-6 lg:px-10">
      <div class="newsletter-text">
        <h3>Stay in the Loop</h3>
        <p>Get updates on scholarships, events, and impact stories — straight to your inbox.</p>
      </div>
      <form class="newsletter-form" id="newsletter-form" novalidate>
        <input
          type="email"
          name="newsletter_email"
          id="newsletter-email"
          placeholder="Enter your email address"
          class="newsletter-input"
          required
        />
        <button type="submit" id="newsletter-submit-btn" class="newsletter-btn">
          Subscribe
        </button>
      </form>
    </div>
    <div id="newsletter-status" style="display:none; text-align:center; margin-top:0.75rem; font-size:0.875rem; font-weight:600;"></div>
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
              <div class="text-foreground font-medium">
                <a href="mailto:info@prayogbharti.org" class="hover:text-primary transition-colors">info@prayogbharti.org</a>
              </div>
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
              <div class="text-foreground font-medium text-sm leading-relaxed">
                Plot No. 255-A & 256, G/F, Shyam Vihar, E-Block Extension, Najafgarh, South West New Delhi – 110043, India
              </div>
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

<!-- SERVICES DETAIL MODAL -->
<!-- The close button uses a plain text × and an inline onclick so that Lucide icon -->
<!-- re-renders (which replace <i> tags with fresh <svg> nodes) cannot break the handler. -->
<div id="service-modal"
     style="display:none; position:fixed; inset:0; z-index:9000; align-items:center; justify-content:center; padding:1rem; background:rgba(0,0,0,0.6);"
     aria-modal="true" role="dialog">
  <div id="service-modal-content"
       style="background:var(--card,#fff); border:1px solid var(--border,#e5e7eb); border-radius:1rem; max-width:32rem; width:100%; padding:2rem; box-shadow:0 25px 50px -12px rgba(0,0,0,.25); position:relative;">

    <!-- Close button: plain × text, no Lucide icon, inline onclick -->
    <button
      id="service-modal-close"
      onclick="pbCloseServiceModal()"
      aria-label="Close"
      style="position:absolute; top:1rem; right:1rem; z-index:9999; cursor:pointer; pointer-events:auto;
             width:2rem; height:2rem; display:flex; align-items:center; justify-content:center;
             border-radius:9999px; border:none; background:transparent;
             font-size:1.25rem; line-height:1; color:var(--muted-foreground,#6b7280);"
      onmouseenter="this.style.background='var(--muted,#f3f4f6)'"
      onmouseleave="this.style.background='transparent'">
      &#x2715;
    </button>

    <!-- Header -->
    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; padding-right:2.5rem;">
      <div style="background:rgba(30,107,60,.1); border-radius:.75rem; padding:.75rem; color:var(--primary,#1E6B3C); flex-shrink:0;">
        <svg id="service-modal-icon-svg" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
      </div>
      <h3 id="modal-service-title" style="font-family:'Playfair Display',serif; font-size:1.375rem; font-weight:700; color:var(--foreground,#1a2e1a); margin:0;">Service Detail</h3>
    </div>

    <!-- Description -->
    <p id="modal-service-description" style="color:var(--muted-foreground,#5a7a60); font-size:.875rem; line-height:1.625; margin-bottom:1.5rem;"></p>

    <!-- Focus areas (hidden until populated) -->
    <div id="modal-service-details-extra" style="display:none; margin-bottom:2rem;">
      <h4 style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--primary,#1E6B3C); margin-bottom:.75rem;">Key Focus Areas</h4>
      <ul id="modal-service-list" style="list-style:disc; padding-left:1.25rem; font-size:.75rem; color:var(--muted-foreground,#5a7a60); line-height:1.75;"></ul>
    </div>

    <!-- CTA: button element so main.js smooth-scroll cannot intercept it -->
    <button
      id="modal-service-cta"
      onclick="pbModalCTA()"
      style="display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%;
             background:var(--primary,#1E6B3C); color:#fff; font-weight:600; padding:.75rem 1rem;
             border-radius:.75rem; border:none; cursor:pointer; font-size:.875rem;
             transition:background .2s;"
      onmouseenter="this.style.background='var(--accent,#d4edda)'"
      onmouseleave="this.style.background='var(--primary,#1E6B3C)'">
      Enquire About This Service
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
           fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </button>
  </div>
</div>

<script>
// ─── Global modal helpers (defined outside DOMContentLoaded)
function pbOpenServiceModal() {
  var el = document.getElementById('service-modal');
  if (el) { el.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}
function pbCloseServiceModal() {
  var el = document.getElementById('service-modal');
  if (el) { el.style.display = 'none'; document.body.style.overflow = ''; }
}
function pbModalCTA() {
  pbCloseServiceModal();
  var title = document.getElementById('modal-service-title');
  var selectEl = document.querySelector('select[name="interest"]');
  if (title && selectEl) {
    var name = title.textContent || '';
    if (name.includes('Scholarship')) {
      selectEl.value = 'Applying for Scholarship';
    } else if (name.includes('Mentorship')) {
      selectEl.value = 'Volunteering / Mentoring';
    } else if (name.includes('Development') || name.includes('Research') || name.includes('Internship')) {
      selectEl.value = 'Partnership / Collaboration';
    } else {
      selectEl.value = 'General Enquiry';
    }
  }
  // Smooth-scroll to contact section
  var contactEl = document.getElementById('contact');
  if (contactEl) {
    var offset = 104;
    var top = contactEl.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top: top, behavior: 'smooth' });
  }
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { pbCloseServiceModal(); }
});

// Close on overlay click (click on the dark backdrop, not the white box)
document.addEventListener('click', function(e) {
  var modal = document.getElementById('service-modal');
  if (modal && modal.style.display === 'flex') {
    if (e.target === modal) {
      pbCloseServiceModal();
    }
  }
});

document.addEventListener('DOMContentLoaded', () => {
  // Testimonial highlight on click
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

  // Delegated click listener for Service Read More buttons
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.service-read-more');
    if (btn) {
      e.preventDefault();
      
      const title = btn.getAttribute('data-title') || btn.getAttribute('data-name') || '';
      const desc = btn.getAttribute('data-description') || btn.getAttribute('data-desc') || '';
      
      console.log('Service Read More clicked', title);
      
      var titleEl = document.getElementById('modal-service-title');
      var descEl  = document.getElementById('modal-service-description');
      if (titleEl) titleEl.textContent = title;
      if (descEl)  descEl.textContent  = desc;

      var subFocusMap = {
        'Research & Development': [
          'Innovation for Education: Cutting-edge tool integration',
          'EdTech Solutions: Customized interactive learning platforms',
          'Digital Literacy Initiatives: Equipping students and educators',
          'Address Educational Challenges: Enhancing overall learning'
        ],
        'Mentorship': [
          'Tech Mentor Networks: Connecting with industry veterans',
          'Coding and Development Mentors: Fostering practical tech skills',
          'Career Guidance: Assisting in academic and career pathways',
          'Personal Development: Building life skills, confidence, and purpose'
        ],
        'Cyber Security Internship': [
          'Information Security: Core data protection practices',
          'Application Security: Shielding applications from threats',
          'Cloud Security: Implementing secure cloud architectures',
          'DevSecOps: Integrating security testing into dev pipelines'
        ],
        'Application & Website Development': [
          'Front-end Engineering: Clean, premium UI structure',
          'Back-end Architecture: Scalable, robust server routing',
          'Database Integration: Safe parameterized querying',
          'Production Deployment: Optimizing speed, performance, and security'
        ],
        'Digital Marketing Internship': [
          'SEO Best Practices: Enhancing page ranks and indexing',
          'Campaign Management: Analyzing conversion metrics',
          'Content Strategy: Driving user engagement and traffic',
          'Social Media Marketing: Leveraging networks for outreach'
        ]
      };

      var listEl  = document.getElementById('modal-service-list');
      var extraEl = document.getElementById('modal-service-details-extra');
      if (listEl) listEl.innerHTML = '';
      var extras = subFocusMap[title] || [];
      if (extras.length > 0 && listEl && extraEl) {
        extras.forEach(function(item) {
          var li = document.createElement('li');
          li.innerHTML = item.replace(/(^[^:]+:)/, '<strong>$1</strong>');
          listEl.appendChild(li);
        });
        extraEl.style.display = 'block';
      } else if (extraEl) {
        extraEl.style.display = 'none';
      }

      pbOpenServiceModal();
    }
  });
}); // end DOMContentLoaded
</script>
