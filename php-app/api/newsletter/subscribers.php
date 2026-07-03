<?php
/**
 * api/newsletter/subscribers.php
 * Replaces: newsletterController.getSubscribers
 *
 * GET /api/newsletter/subscribers.php - Admin: get subscribers list
 */

require_once __DIR__ . '/../../api/_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

requireRole('admin');

try {
    $pdo = db();
    $activeFilter = $_GET['active'] ?? '';

    if ($activeFilter !== '') {
        $statusVal = $activeFilter === 'true' ? 'Active' : 'Inactive';
        $stmt = $pdo->prepare("SELECT * FROM newsletter_subscribers WHERE status = ? ORDER BY created_at DESC");
        $stmt->execute([$statusVal]);
    } else {
        $stmt = $pdo->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
    }

    $subscribers = $stmt->fetchAll();

    jsonResponse([
        'success' => true,
        'count' => count($subscribers),
        'data' => $subscribers
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
