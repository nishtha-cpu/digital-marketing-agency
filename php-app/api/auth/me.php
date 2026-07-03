<?php
/**
 * api/auth/me.php – GET /api/auth/me
 * Replaces: authController.getUserProfile + GET /api/auth/me route
 * Access: Private (requires token or session)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Resolve user via token header or session
$userId = null;
try {
    $pdo = db();

    // 1. Try X-Auth-Token header
    $headerToken = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';
    if (empty($headerToken)) {
        // Also check Authorization: Bearer <token>
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.+)/i', $authHeader, $m)) {
            $headerToken = $m[1];
        }
    }

    if ($headerToken) {
        $stmt = $pdo->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$headerToken]);
        $row = $stmt->fetch();
        if ($row) $userId = $row['user_id'];
    }

    // 2. Fall back to session
    if (!$userId && !empty($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    }

    if (!$userId) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not authorized, no token provided']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $user]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
