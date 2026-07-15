-- ============================================================
--  Prayogbharti Foundation – MySQL Schema
--  Replaces MongoDB/Mongoose models from Node.js backend
-- ============================================================

CREATE DATABASE IF NOT EXISTS `prayogbharti_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `prayogbharti_db`;

-- ─── Users ────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(255)    NOT NULL,
    `email`      VARCHAR(255)    NOT NULL UNIQUE,
    `password`   VARCHAR(255)    NOT NULL,           -- bcrypt via PHP password_hash()
    `role`       ENUM('admin','author','user') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── User API Tokens ──────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `user_tokens` (
    `id`         INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `user_id`    INT UNSIGNED    NOT NULL,
    `token`      VARCHAR(64)     NOT NULL UNIQUE,
    `expires_at` TIMESTAMP       NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 30 DAY),
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Leads ────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `leads` (
    `id`               INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `name`             VARCHAR(255)    NOT NULL,
    `email`            VARCHAR(255)    NOT NULL,
    `phone`            VARCHAR(50)     DEFAULT NULL,
    `service_interest` VARCHAR(255)    NOT NULL DEFAULT 'General Inquiry',
    `message`          TEXT            NOT NULL,
    `status`           ENUM('new','contacted','closed') NOT NULL DEFAULT 'new',
    `created_at`       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Contacts (Contact Enquiries) ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `contacts` (
    `id`         INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(255)    NOT NULL,
    `email`      VARCHAR(255)    NOT NULL,
    `phone`      VARCHAR(50)     DEFAULT NULL,
    `message`    TEXT            NOT NULL,
    `status`     ENUM('Read','Unread') NOT NULL DEFAULT 'Unread',
    `starred`    TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Blog Posts ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id`           INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `title`        VARCHAR(500)    NOT NULL UNIQUE,
    `slug`         VARCHAR(500)    NOT NULL UNIQUE,
    `summary`      TEXT            NOT NULL,
    `content`      LONGTEXT        NOT NULL,
    `author_id`    INT UNSIGNED    NOT NULL,
    `cover_image`  VARCHAR(1000)   NOT NULL DEFAULT '',
    `tags`         VARCHAR(500)    NOT NULL DEFAULT '',   -- comma-separated
    `status`       ENUM('draft','published') NOT NULL DEFAULT 'draft',
    `created_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Services ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `services` (
    `id`          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(255)    NOT NULL UNIQUE,
    `description` TEXT            NOT NULL,
    `icon`        VARCHAR(100)    NOT NULL DEFAULT 'marketing',
    `features`    TEXT            NOT NULL DEFAULT '',   -- JSON-encoded array
    `price`       VARCHAR(100)    NOT NULL DEFAULT 'Custom Pricing',
    `active`      TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Newsletter Subscribers ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id`         INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(255)    NOT NULL DEFAULT '',
    `email`      VARCHAR(255)    NOT NULL UNIQUE,
    `status`     ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  SEED DATA
-- ============================================================
-- NOTE: Run the UPDATE below after importing if you need to set a custom password.
-- The default hash below is for the password "password".
-- To use Admin@123, run:
--   UPDATE `users` SET `password` = '$2y$10$3xTWHuQXPRMsuUNi3oZ3nOXA6qLEzkaFjcv1Fy5J0x8GknReR/3w.'
--   WHERE `email` = 'admin@prayogbharti.org';

INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`) VALUES
(
    'Admin User',
    'admin@prayogbharti.org',
    '$2y$10$3xTWHuQXPRMsuUNi3oZ3nOXA6qLEzkaFjcv1Fy5J0x8GknReR/3w.', -- password: Admin@123
    'admin'
);

INSERT IGNORE INTO `services` (`name`, `description`, `icon`, `active`) VALUES
('Research & Development', 'Conducting research projects to explore cutting-edge technologies in education and address challenges faced by students and educators.', 'microscope', 1),
('Mentorship', 'Connecting students with experienced professionals, coding mentors, and educators for guidance, skills, and personal growth.', 'users', 1),
('Hackathons', 'Organizing technology competitions and hackathons to foster innovation, collaborative coding, and problem-solving.', 'trophy', 1),
('Workshops & Events', 'Conducting interactive workshops and events on digital literacy, technology trends, and modern learning practices.', 'calendar', 1),
('Corporate Solutions', 'Providing tailored technical services, consulting, and solutions for organizations and corporate partners.', 'briefcase', 1),
('Entrepreneurship', 'Supporting individuals in tech startup development, innovation coaching, and entrepreneurial skill building.', 'lightbulb', 1),
('Skill Empowerment', 'Providing students with tools, guidance, resources, and coaching required for academic and professional growth.', 'award', 1),
('Career Counselling', 'Assisting individuals in making informed decisions about their careers, skills development, and job market trends.', 'compass', 1),
('Latest Technology Training', 'Offering coaching and hands-on training in coding, software development, and new technology fields.', 'cpu', 1),
('Live Industry Projects', 'Engaging students in practical, real-world development projects to build industry-relevant experience.', 'git-branch', 1),
('Cyber Security Internship', 'Hands-on internship programs covering Information Security, Application Security, Cloud Security, and DevSecOps.', 'shield', 1),
('Application & Website Development', 'Fostering development skills through designing, building, and deploying real-world software applications and websites.', 'layout', 1),
('Digital Marketing Internship', 'Practical training in digital marketing strategies, campaign management, and digital landscape navigation.', 'trending-up', 1);

INSERT IGNORE INTO `blog_posts` (`title`, `slug`, `summary`, `content`, `author_id`, `cover_image`, `tags`, `status`) VALUES
(
    'Innovation in Education through Technology',
    'innovation-education-through-technology',
    'Exploring how cutting-edge technology, EdTech solutions, and digital literacy initiatives are transforming learning environments and fostering innovation.',
    '<p class="mb-6">At Prayogbharti Foundation, we are committed to fostering positive change through research and development initiatives. Technology is at the heart of this transformation, playing a key role in empowering individuals across different educational levels.</p><p class="mb-6">Our research projects explore the integration of cutting-edge technologies in educational settings, enhancing teaching methods and learning experiences. By designing interactive and inclusive environments, we help educators and students connect in more meaningful ways.</p><h3 class="text-2xl font-bold mb-4 mt-8" style="font-family: \'Playfair Display\', serif;">Specific R&D Initiatives</h3><ul class="list-disc pl-6 mb-6 space-y-2"><li><strong>Innovation for Education:</strong> Researching new pedagogical tools and technologies to make classrooms more engaging.</li><li><strong>EdTech Solutions:</strong> Developing applications and platforms tailored to meet local educational challenges.</li><li><strong>Digital Literacy:</strong> Equipping students and teachers with the skills necessary to safely and effectively navigate the digital world.</li></ul><p class="mb-6">Through these initiatives, our goal is to provide students and communities with the tools, guidance, resources, and opportunities required for personal, academic, and professional growth.</p>',
    1,
    'assets/images/blog1.jpg',
    'Innovation',
    'published'
),
(
    'Expanding Access through STEM Scholarships',
    'expanding-access-through-stem-scholarships',
    'How providing financial support, merit-based assistance, and tech access empowers underrepresented students in science and engineering fields.',
    '<p class="mb-6">Education is a fundamental right, yet many deserving students face financial and structural barriers that prevent them from pursuing their dreams. Prayogbharti Foundation’s Scholarship Programs are designed to bridge this gap, ensuring that talent alone determines a student’s future.</p><p class="mb-6">We provide dedicated STEM scholarships, financial access, and tech access for students from economically disadvantaged and underrepresented backgrounds. This support goes beyond financial assistance; it provides students with the technology resources they need to thrive in a digital-first economy.</p><h3 class="text-2xl font-bold mb-4 mt-8" style="font-family: \'Playfair Display\', serif;">Key Scholarship Features</h3><ul class="list-disc pl-6 mb-6 space-y-2"><li><strong>STEM Scholarships:</strong> Focused on Science, Technology, Engineering, and Mathematics disciplines.</li><li><strong>Tech & Financial Access:</strong> Providing laptops, internet access, and tuition fees to eliminate learning barriers.</li><li><strong>Inclusivity & Merit Support:</strong> Recognizing academic excellence while promoting opportunities for underrepresented communities.</li></ul><p class="mb-6">By investing in these future leaders, we are not only supporting individual academic journeys but also contributing to the advancement of society as a whole.</p>',
    1,
    'assets/images/blog2.jpg',
    'Scholarships',
    'published'
),
(
    'Mentorship and Career Guidance for Future Leaders',
    'mentorship-career-guidance-future-leaders',
    'Connecting aspiring students with experienced technology professionals and educators to build career pathways and key life skills.',
    '<p class="mb-6">Knowledge is powerful, but guidance is the compass that points it in the right direction. Through our Mentorship Programs, we connect students with tech professionals, developers, educators, and community leaders who volunteer their time and expertise.</p><p class="mb-6">Mentees receive career guidance, hands-on skills training, and personal development coaching. By establishing strong mentor-mentee networks, we prepare individuals for future opportunities and inspire them to become active contributors to their communities.</p><h3 class="text-2xl font-bold mb-4 mt-8" style="font-family: \'Playfair Display\', serif;">Mentorship Highlights</h3><ul class="list-disc pl-6 mb-6 space-y-2"><li><strong>Tech Mentor Networks:</strong> Direct interaction with industry professionals from top technology sectors.</li><li><strong>Practical Coding Mentors:</strong> Hands-on coaching in software development and technical project management.</li><li><strong>Personal Development:</strong> Seminars and workshops focusing on communication, confidence, and leadership skills.</li></ul><p class="mb-6">Our structured mentorship pathways help transition students from academic environments into industry-ready contributors, paving the way for sustainable career success.</p>',
    1,
    'assets/images/blog3.jpg',
    'Mentorship',
    'published'
);
