<?php
/**
 * blog-details.php – Detailed blog post viewer
 * Prayogbharti Foundation
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';

/* ── Page Meta ─────────────────────────────────────────── */
$pageTitle       = 'Prayogbharti Foundation – Stories of Impact';
$metaDescription = 'Read our stories of positive change and educational initiatives.';

$blogId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = null;

// Try to fetch from DB
try {
    $stmt = db()->prepare("
        SELECT bp.*, u.name AS author_name
        FROM blog_posts bp
        LEFT JOIN users u ON bp.author_id = u.id
        WHERE bp.id = ? AND bp.status = 'published'
    ");
    $stmt->execute([$blogId]);
    $post = $stmt->fetch();
} catch (Exception $e) {
    // Database connection failed or query failed, fall back
}

// Fallback to sample posts if DB fetch failed or empty
$fallbackBlogs = [
    1 => [
        'id' => 1,
        'title' => 'Innovation in Education through Technology',
        'summary' => 'Exploring how cutting-edge technology, EdTech solutions, and digital literacy initiatives are transforming learning environments and fostering innovation.',
        'content' => '
            <p class="mb-6">At Prayogbharti Foundation, we are committed to fostering positive change through research and development initiatives. Technology is at the heart of this transformation, playing a key role in empowering individuals across different educational levels.</p>
            <p class="mb-6">Our research projects explore the integration of cutting-edge technologies in educational settings, enhancing teaching methods and learning experiences. By designing interactive and inclusive environments, we help educators and students connect in more meaningful ways.</p>
            <h3 class="text-2xl font-bold mb-4 mt-8 text-foreground" style="font-family: \'Playfair Display\', serif;">Specific R&D Initiatives</h3>
            <ul class="list-disc pl-6 mb-6 space-y-3">
                <li><strong>Innovation for Education:</strong> Researching new pedagogical tools and technologies to make classrooms more engaging and intuitive for modern students.</li>
                <li><strong>EdTech Solutions:</strong> Developing applications and platforms tailored to meet local educational challenges, fostering interactive environments.</li>
                <li><strong>Digital Literacy:</strong> Equipping students and teachers with the skills necessary to safely and effectively navigate the digital world.</li>
                <li><strong>Promote Innovation:</strong> Supporting student-led R&D projects that explore creative solutions to common educational challenges.</li>
            </ul>
            <p class="mb-6">Through these initiatives, our goal is to provide students and communities with the tools, guidance, resources, and opportunities required for personal, academic, and professional growth.</p>
        ',
        'tags' => 'Innovation',
        'created_at' => '2026-07-10',
        'cover_image' => 'assets/images/blog1.jpg',
        'author_name' => 'Prayogbharti Foundation'
    ],
    2 => [
        'id' => 2,
        'title' => 'Expanding Access through STEM Scholarships',
        'summary' => 'How providing financial support, merit-based assistance, and tech access empowers underrepresented students in science and engineering fields.',
        'content' => '
            <p class="mb-6">Education is a fundamental right, yet many deserving students face financial and structural barriers that prevent them from pursuing their dreams. Prayogbharti Foundation’s Scholarship Programs are designed to bridge this gap, ensuring that talent alone determines a student’s future.</p>
            <p class="mb-6">We provide dedicated STEM scholarships, financial access, and tech access for students from economically disadvantaged and underrepresented backgrounds. This support goes beyond financial assistance; it provides students with the technology resources they need to thrive in a digital-first economy.</p>
            <h3 class="text-2xl font-bold mb-4 mt-8 text-foreground" style="font-family: \'Playfair Display\', serif;">Key Scholarship Features</h3>
            <ul class="list-disc pl-6 mb-6 space-y-3">
                <li><strong>STEM Scholarships:</strong> Encouraging interest and academic excellence in Science, Technology, Engineering, and Mathematics.</li>
                <li><strong>Tech Access:</strong> Providing hardware resources like laptops, tablets, and internet connectivity to bridge the digital divide.</li>
                <li><strong>Financial Assistance:</strong> Awarding tuition support to alleviate financial stress for high-performing students.</li>
                <li><strong>Inclusive Opportunities:</strong> Reaching out to students from rural, remote, and underrepresented communities.</li>
            </ul>
            <p class="mb-6">By investing in these future leaders, we are not only supporting individual academic journeys but also contributing to the advancement of society as a whole.</p>
        ',
        'tags' => 'Scholarships',
        'created_at' => '2026-07-05',
        'cover_image' => 'assets/images/blog2.jpg',
        'author_name' => 'Prayogbharti Foundation'
    ],
    3 => [
        'id' => 3,
        'title' => 'Mentorship and Career Guidance for Future Leaders',
        'summary' => 'Connecting aspiring students with experienced technology professionals and educators to build career pathways and key life skills.',
        'content' => '
            <p class="mb-6">Knowledge is powerful, but guidance is the compass that points it in the right direction. Through our Mentorship Programs, we connect students with tech professionals, developers, educators, and community leaders who volunteer their time and expertise.</p>
            <p class="mb-6">Mentees receive career guidance, hands-on skills training, and personal development coaching. By establishing strong mentor-mentee networks, we prepare individuals for future opportunities and inspire them to become active contributors to their communities.</p>
            <h3 class="text-2xl font-bold mb-4 mt-8 text-foreground" style="font-family: \'Playfair Display\', serif;">Mentorship Highlights</h3>
            <ul class="list-disc pl-6 mb-6 space-y-3">
                <li><strong>Tech Mentor Networks:</strong> Connecting students directly with tech leaders for professional development advice and career mapping.</li>
                <li><strong>Practical Coding Mentors:</strong> Fostering hands-on training in software development, design, and new tools.</li>
                <li><strong>Personal Development:</strong> Mentoring focus on soft skills, communication, problem solving, and confidence.</li>
                <li><strong>Career Counselling:</strong> Guidance on resume building, networking, and industry-ready resources.</li>
            </ul>
            <p class="mb-6">Our structured mentorship pathways help transition students from academic environments into industry-ready contributors, paving the way for sustainable career success.</p>
        ',
        'tags' => 'Mentorship',
        'created_at' => '2026-06-28',
        'cover_image' => 'assets/images/blog3.jpg',
        'author_name' => 'Prayogbharti Foundation'
    ]
];

if (!$post && isset($fallbackBlogs[$blogId])) {
    $post = $fallbackBlogs[$blogId];
}

// Redirect back to home if post is not found anywhere
if (!$post) {
    header("Location: index.php");
    exit;
}

$pageTitle = htmlspecialchars($post['title']) . ' – Prayogbharti Foundation';
$metaDescription = htmlspecialchars($post['summary']);

include __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-background text-foreground" style="font-family: 'Nunito', sans-serif;">
  <?php include __DIR__ . '/includes/navbar.php'; ?>

  <main class="max-w-4xl mx-auto px-6 lg:px-10 pt-40 pb-24">
    <!-- Breadcrumbs / Back button -->
    <div class="mb-8">
      <a href="index.php#blog" class="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all duration-200 text-sm">
        <i data-lucide="arrow-left" width="16" height="16"></i> Back to Blog
      </a>
    </div>

    <!-- Blog Header -->
    <header class="mb-10">
      <div class="flex items-center gap-3 mb-4">
        <span class="bg-primary/10 text-primary text-xs font-bold uppercase tracking-wide px-3 py-1 rounded-full">
          <?= htmlspecialchars($post['tags']) ?>
        </span>
        <span class="text-muted-foreground text-xs font-medium" style="font-family: 'DM Mono', monospace">
          <?= date("F j, Y", strtotime($post['created_at'])) ?>
        </span>
      </div>
      <h1 class="text-4xl lg:text-5xl font-bold text-foreground leading-tight" style="font-family: 'Playfair Display', serif;">
        <?= htmlspecialchars($post['title']) ?>
      </h1>
      <div class="flex items-center gap-3 mt-6">
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
          <?= htmlspecialchars(substr($post['author_name'] ?? 'Prayogbharti Foundation', 0, 1)) ?>
        </div>
        <div>
          <div class="text-sm font-semibold text-foreground"><?= htmlspecialchars($post['author_name'] ?? 'Prayogbharti Foundation') ?></div>
          <div class="text-xs text-muted-foreground">Author</div>
        </div>
      </div>
    </header>

    <!-- Blog Cover Image -->
    <?php if (!empty($post['cover_image'])): 
      // If cover image is Unsplash or online URL, use it; otherwise use local asset path
      $imgSrc = (strpos($post['cover_image'], 'http') === 0) ? $post['cover_image'] : $post['cover_image'];
    ?>
      <div class="rounded-2xl overflow-hidden aspect-[21/9] bg-muted mb-12 shadow-md">
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover" />
      </div>
    <?php endif; ?>

    <!-- Blog Content -->
    <article class="prose max-w-none text-muted-foreground text-base leading-relaxed mb-16">
      <?= $post['content'] ?>
    </article>

    <!-- Call to Action -->
    <section class="border border-border bg-secondary/50 rounded-2xl p-8 text-center max-w-2xl mx-auto">
      <h3 class="text-xl font-bold text-foreground mb-3" style="font-family: 'Playfair Display', serif;">Empower Education with Us</h3>
      <p class="text-sm text-muted-foreground mb-6 max-w-md mx-auto">
        Join Prayogbharti Foundation's efforts in supporting students and communities across India.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="index.php#contact" class="inline-flex items-center gap-2 bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-full hover:bg-accent transition-colors">
          Get Involved <i data-lucide="heart" fill="currentColor" width="12" height="12"></i>
        </a>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</div>
