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
('Scholarships',       'Providing financial assistance for deserving students to pursue their education without barriers.',  'Award',    1),
('Coaching Services',  'Offering personalized coaching to enhance academic skills and performance in STEM disciplines.',     'BookOpen', 1),
('Mentorship Programs','Connecting students with experienced mentors for guidance, support, and career direction.',           'Users',    1),
('Community Outreach', 'Engaging with communities to promote educational opportunities and inspire local talent.',            'Globe',    1);

INSERT IGNORE INTO `blog_posts` (`title`, `slug`, `summary`, `content`, `author_id`, `cover_image`, `tags`, `status`) VALUES
(
    'Breaking Barriers: How STEM Education Is Changing Rural India',
    'breaking-barriers-stem-education-rural-india',
    'Across villages in Maharashtra and Rajasthan, a quiet revolution is underway — one textbook at a time.',
    '<p>Across villages in Maharashtra and Rajasthan, a quiet revolution is underway — one textbook at a time. STEM education is opening doors that were once firmly closed to rural students.</p><p>With the support of dedicated teachers and volunteers, young minds are discovering the power of science, technology, engineering, and mathematics to transform their futures.</p>',
    1,
    'https://images.unsplash.com/flagged/photo-1574097656146-0b43b7660cb6?w=600&h=400&fit=crop&auto=format',
    'Education',
    'published'
),
(
    'Meet the Mentors: Professionals Who Give Back to the Community',
    'meet-the-mentors-professionals-give-back',
    'From IIT graduates to doctors and engineers — the volunteers who spend weekends shaping young minds.',
    '<p>From IIT graduates to doctors and engineers — these remarkable volunteers give up their weekends to mentor the next generation. Their stories are inspiring proof that success is most meaningful when shared.</p>',
    1,
    'https://images.unsplash.com/photo-1761666520005-3ffcf13e74c8?w=600&h=400&fit=crop&auto=format',
    'Mentorship',
    'published'
),
(
    'Scholarship Stories: The Faces Behind Our 2024 Annual Report',
    'scholarship-stories-2024-annual-report',
    'We sat down with five scholarship recipients to understand what financial support truly means.',
    '<p>We sat down with five scholarship recipients to understand what financial support truly means. Behind every scholarship is a story of perseverance, hope, and the transformative power of education.</p>',
    1,
    'https://images.unsplash.com/photo-1652648265326-73317a42c43d?w=600&h=400&fit=crop&auto=format',
    'Impact',
    'published'
);
