<?php
/**
 * Shared API helper functions.
 * Included by all API endpoint files.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

/**
 * Verify an API token or session.
 * Returns the authenticated user array or null.
 */
function getAuthUser(): ?array {
    $pdo = db();
    $userId = null;

    // 1. X-Auth-Token or Authorization Bearer header
    $token = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
    if (empty($token)) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.+)/i', $authHeader, $m)) {
            $token = $m[1];
        }
    }
    if ($token) {
        $stmt = $pdo->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $row = $stmt->fetch();
        if ($row) $userId = $row['user_id'];
    }

    // 2. Session fallback
    if (!$userId && !empty($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    }

    if (!$userId) return null;

    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

/**
 * Respond with JSON and exit.
 */
function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Enforce authentication, return user or abort with 401.
 */
function requireAuth(): array {
    $user = getAuthUser();
    if (!$user) {
        jsonResponse(['success' => false, 'message' => 'Not authorized, no token provided'], 401);
    }
    return $user;
}

/**
 * Enforce a specific role.
 */
function requireRole(string ...$roles): array {
    $user = requireAuth();
    if (!in_array($user['role'], $roles)) {
        jsonResponse(['success' => false, 'message' => "User role '{$user['role']}' is not authorized"], 403);
    }
    return $user;
}
