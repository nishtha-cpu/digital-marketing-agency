<?php
/**
 * Application Configuration
 * Prayogbharti Foundation – Digital Marketing Agency
 *
 * Update DB_HOST, DB_NAME, DB_USER, DB_PASS to match your XAMPP MySQL setup.
 */

// ─── Database ──────────────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'prayogbharti_db');
define('DB_USER', 'root');
define('DB_PASS', '');          // XAMPP default is empty password
define('DB_CHARSET', 'utf8mb4');

// ─── Application ───────────────────────────────────────────────────────────
// Change BASE_URL to match your XAMPP virtual host or subfolder.
// e.g. 'http://localhost/prayogbharti' if placed in htdocs/prayogbharti/
define('BASE_URL', 'http://localhost/php-app');

// Secret key used to generate/validate API tokens (replaces JWT_SECRET)
define('APP_SECRET', 'prayogbharti_php_secret_key_2025');

// Session cookie name
define('SESSION_NAME', 'pb_session');

// Environment: 'development' | 'production'
define('APP_ENV', 'development');

// ─── Session Start ─────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
