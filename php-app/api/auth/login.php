<?php
/**
 * api/auth/login.php – POST /api/auth/login
 * Replaces: authController.loginUser + POST /api/auth/login route
 * Access: Public
 *
 * Also sets a PHP session so admin pages can authenticate without
 * sending token headers on every request.
 */

require_once __DIR__ . '/../../config/config.php';  // starts session
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$body     = json_decode(file_get_contents('php://input'), true);
$email    = strtolower(trim($body['email']    ?? ''));
$password = $body['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide email and password']);
    exit;
}

try {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        exit;
    }

    // Generate / refresh API token
    $token = bin2hex(random_bytes(32));
    // Remove old tokens for this user and insert new one
    $pdo->prepare("DELETE FROM user_tokens WHERE user_id = ?")->execute([$user['id']]);
    $pdo->prepare("INSERT INTO user_tokens (user_id, token) VALUES (?, ?)")->execute([$user['id'], $token]);

    // Set PHP session (for admin dashboard pages)
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['role']      = $user['role'];
    $_SESSION['user_name'] = $user['name'];

    echo json_encode([
        'success' => true,
        'data' => [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'token' => $token,
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
