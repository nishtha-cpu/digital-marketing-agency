<?php
/**
 * api/services/admin.php
 * Replaces: serviceController.getAdminServices
 *
 * GET /api/services/admin.php - Admin: get all services
 */

require_once __DIR__ . '/../_helpers.php';

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
    $stmt = $pdo->query("SELECT * FROM services ORDER BY name ASC");
    $services = $stmt->fetchAll();

    foreach ($services as &$s) {
        if (!empty($s['features'])) {
            $decoded = json_decode($s['features'], true);
            $s['features'] = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $s['features'])));
        } else {
            $s['features'] = [];
        }
    }
    unset($s);

    jsonResponse([
        'success' => true,
        'count' => count($services),
        'data' => $services
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
