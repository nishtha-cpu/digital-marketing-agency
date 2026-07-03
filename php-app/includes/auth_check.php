<?php
/**
 * auth_check.php – Session-based authentication guard for admin pages.
 * Replaces JWT middleware (authMiddleware.js) for browser-based admin routes.
 *
 * Usage: include this file at the top of every admin/*.php page.
 */

require_once __DIR__ . '/../config/config.php';  // starts session

if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

/**
 * Optionally enforce a specific role.
 * Example: requireRole('admin');
 */
function requireRole(string $role): void {
    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        die('<p style="font-family:sans-serif;text-align:center;margin-top:4rem;">403 – You do not have permission to access this page.</p>');
    }
}

/**
 * Returns the currently logged-in user's ID from session.
 */
function currentUserId(): int {
    return (int) ($_SESSION['user_id'] ?? 0);
}

/**
 * Returns the currently logged-in user's role from session.
 */
function currentRole(): string {
    return $_SESSION['role'] ?? 'user';
}

/**
 * Returns current user's name from session (if set during login).
 */
function currentName(): string {
    return $_SESSION['user_name'] ?? 'Admin';
}
