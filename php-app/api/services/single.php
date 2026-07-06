<?php
/**
 * api/services/single.php
 * Replaces: serviceController.getServiceById, serviceController.updateService, serviceController.deleteService
 *
 * GET    /api/services/single.php?id=X  - Public: get service by ID
 * PUT    /api/services/single.php?id=X  - Admin: update service
 * DELETE /api/services/single.php?id=X  - Admin: delete service
 */

require_once __DIR__ . '/../_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$id = (int)($_GET['id'] ?? 0);
$pdo = db();

if (!$id) {
    jsonResponse(['success' => false, 'message' => 'Service ID is required'], 400);
}

if ($method === 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch();

        if (!$service) {
            jsonResponse(['success' => false, 'message' => 'Service not found'], 404);
        }

        if (!empty($service['features'])) {
            $decoded = json_decode($service['features'], true);
            $service['features'] = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $service['features'])));
        } else {
            $service['features'] = [];
        }

        jsonResponse(['success' => true, 'data' => $service]);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

if ($method === 'PUT') {
    requireRole('admin');

    try {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch();

        if (!$service) {
            jsonResponse(['success' => false, 'message' => 'Service not found'], 404);
        }

        $body = json_decode(file_get_contents('php://input'), true);
        $name = isset($body['name']) ? trim($body['name']) : $service['name'];
        $description = isset($body['description']) ? trim($body['description']) : $service['description'];
        $icon = isset($body['icon']) ? trim($body['icon']) : $service['icon'];
        $price = isset($body['price']) ? trim($body['price']) : $service['price'];
        $active = isset($body['active']) ? (int)$body['active'] : (int)$service['active'];
        
        $features = $service['features'];
        if (isset($body['features'])) {
            if (is_array($body['features'])) {
                $features = json_encode($body['features']);
            } else if (is_string($body['features'])) {
                $features = json_encode(array_filter(array_map('trim', explode(',', $body['features']))));
            }
        }

        $stmt = $pdo->prepare("UPDATE services SET name = ?, description = ?, icon = ?, features = ?, price = ?, active = ? WHERE id = ?");
        $stmt->execute([$name, $description, $icon, $features, $price, $active, $id]);

        jsonResponse([
            'success' => true,
            'message' => 'Service updated successfully'
        ]);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

if ($method === 'DELETE') {
    requireRole('admin');

    try {
        $stmt = $pdo->prepare("SELECT id FROM services WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            jsonResponse(['success' => false, 'message' => 'Service not found'], 404);
        }

        $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
        jsonResponse(['success' => true, 'message' => 'Service deleted successfully']);
    } catch (Exception $e) {
        jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
