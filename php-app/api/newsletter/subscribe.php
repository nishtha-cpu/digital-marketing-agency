<?php
/**
 * api/newsletter/subscribe.php
 * Replaces: newsletterController.subscribe
 *
 * POST /api/newsletter/subscribe.php - Public
 */

require_once __DIR__ . '/../../api/_helpers.php';

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

    // Check if subscriber exists
    $stmt = $pdo->prepare("SELECT id, status FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $subscriber = $stmt->fetch();

    if ($subscriber) {
        if ($subscriber['status'] === 'Active') {
            jsonResponse(['success' => false, 'message' => 'Email is already subscribed'], 400);
        } else {
            // Reactivate subscription
            $pdo->prepare("UPDATE newsletter_subscribers SET status = 'Active' WHERE id = ?")->execute([$subscriber['id']]);
            jsonResponse([
                'success' => true,
                'message' => 'Subscribed to newsletter successfully (reactivated)'
            ]);
        }
    }

    // Create new subscription
    $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email, status) VALUES (?, 'Active')");
    $stmt->execute([$email]);

    jsonResponse([
        'success' => true,
        'message' => 'Subscribed to newsletter successfully'
    ], 201);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
