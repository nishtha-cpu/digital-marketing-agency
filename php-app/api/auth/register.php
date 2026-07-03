<?php
/**
 * api/auth/register.php – POST /api/auth/register
 * Replaces: authController.registerUser + POST /api/auth/register route
 * Access: Public
 */

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$name     = trim($body['name']     ?? '');
$email    = strtolower(trim($body['email']    ?? ''));
$password = $body['password'] ?? '';
$role     = $body['role']     ?? 'user';

// Validate required fields
if (!$name || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter all fields']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please add a valid email']);
    exit;
}
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

try {
    $pdo = db();

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User already exists']);
        exit;
    }

    // First registered user becomes admin
    $count = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $userRole = ($count === 0) ? 'admin' : ($role ?: 'user');
    $validRoles = ['admin', 'author', 'user'];
    if (!in_array($userRole, $validRoles)) $userRole = 'user';

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword, $userRole]);
    $userId = $pdo->lastInsertId();

    // Generate API token
    $token = bin2hex(random_bytes(32));
    $pdo->prepare("INSERT INTO user_tokens (user_id, token) VALUES (?, ?)")->execute([$userId, $token]);

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'data' => [
            'id'    => $userId,
            'name'  => $name,
            'email' => $email,
            'role'  => $userRole,
            'token' => $token,
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
