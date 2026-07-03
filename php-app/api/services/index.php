<?php
/**
 * api/services/index.php
 * Replaces: serviceController.getServices + serviceController.createService
 *
 * GET  /api/services/index.php  - Public: get active services
 * POST /api/services/index.php  - Admin: create a service
 */

require_once __DIR__ . '/../../api/_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$pdo = db();

if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM services WHERE active = 1 ORDER BY name ASC");
        $services = $stmt->fetchAll();
        
        // Convert features from string back to array if stored as comma-separated or json
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
}

if ($method === 'POST') {
    requireRole('admin');

    $body = json_decode(file_get_contents('php://input'), true);
    $name = trim($body['name'] ?? '');
    $description = trim($body['description'] ?? '');
    $icon = trim($body['icon'] ?? 'marketing');
    $features = $body['features'] ?? '';
    $price = trim($body['price'] ?? 'Custom Pricing');
    $active = isset($body['active']) ? (int)$body['active'] : 1;

    if (!$name || !$description) {
        jsonResponse(['success' => false, 'message' => 'Please enter name and description'], 400);
    }

    try {
        // Check if service exists
        $stmt = $pdo->prepare("SELECT id FROM services WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Service with this name already exists'], 400);
        }

        // Format features to JSON string for MySQL storage
        $featuresStr = '';
        if (is_array($features)) {
            $featuresStr = json_encode($features);
        } else if (is_string($features)) {
            $featuresStr = json_encode(array_filter(array_map('trim', explode(',', $features))));
        }

        $stmt = $pdo->prepare("INSERT INTO services (name, description, icon, features, price, active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $icon, $featuresStr, $price, $active]);
        $id = $pdo->lastInsertId();

        jsonResponse([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => [
                'id' => $id,
                'name' => $name,
                'description' => $description
            ]
        ], 201);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
