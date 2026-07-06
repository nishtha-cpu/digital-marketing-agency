<?php
/**
 * api/leads/status.php
 * Replaces: leadController.updateLeadStatus + PATCH /api/leads/:id/status
 *
 * PATCH /api/leads/status.php?id=X – Admin: update lead status
 */

require_once __DIR__ . '/../_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PATCH, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// Accept both PATCH and POST (some clients don't support PATCH)
if (!in_array($_SERVER['REQUEST_METHOD'], ['PATCH', 'POST'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

requireRole('admin');

$id     = (int) ($_GET['id'] ?? 0);
$body   = json_decode(file_get_contents('php://input'), true);
$status = $body['status'] ?? '';

if (!$id) jsonResponse(['success' => false, 'message' => 'Lead ID is required'], 400);

$validStatuses = ['new', 'contacted', 'closed'];
if (!$status || !in_array($status, $validStatuses)) {
    jsonResponse(['success' => false, 'message' => 'Please provide a valid status: new, contacted, or closed'], 400);
}

try {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT id FROM leads WHERE id = ?");
    $stmt->execute([$id]);
    if (!$stmt->fetch()) jsonResponse(['success' => false, 'message' => 'Lead not found'], 404);

    $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?")->execute([$status, $id]);

    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->execute([$id]);
    $lead = $stmt->fetch();

    jsonResponse([
        'success' => true,
        'message' => "Lead status updated to {$status}",
        'data'    => $lead
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
