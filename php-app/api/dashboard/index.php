<?php
/**
 * api/dashboard/index.php
 * Replaces: dashboardController.getDashboardStats
 *
 * GET /api/dashboard/index.php - Admin: get dashboard statistics
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

    $totalLeads = (int) $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
    $totalBlogs = (int) $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
    $totalServices = (int) $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $totalSubscribers = (int) $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'Active'")->fetchColumn();

    jsonResponse([
        'success' => true,
        'data' => [
            'totalLeads' => $totalLeads,
            'totalBlogs' => $totalBlogs,
            'totalServices' => $totalServices,
            'totalSubscribers' => $totalSubscribers,
        ]
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
