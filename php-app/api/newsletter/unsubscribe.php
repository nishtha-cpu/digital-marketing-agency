<?php
/**
 * api/newsletter/unsubscribe.php
 * Replaces: newsletterController.unsubscribe
 *
 * POST /api/newsletter/unsubscribe.php - Public
 */

require_once __DIR__ . '/../_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$email = strtolower(trim($body['email'] ?? ''));

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['success' => false, 'message' => 'Please provide a valid email address'], 400);
}

try {
    $pdo = db();

    $stmt = $pdo->prepare("SELECT id, status FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $subscriber = $stmt->fetch();

    if (!$subscriber || $subscriber['status'] === 'Inactive') {
        jsonResponse(['success' => false, 'message' => 'Email subscription not found or already inactive'], 404);
    }

    $pdo->prepare("UPDATE newsletter_subscribers SET status = 'Inactive' WHERE id = ?")->execute([$subscriber['id']]);

    jsonResponse([
        'success' => true,
        'message' => 'Unsubscribed from newsletter successfully'
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
