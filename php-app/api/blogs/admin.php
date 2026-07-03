<?php
/**
 * api/blogs/admin.php – GET /api/blogs/admin.php
 * Replaces: blogController.getAdminBlogPosts + GET /api/blogs/admin/all
 * Access: Private (Admin/Author)
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

$user = requireRole('admin', 'author');

try {
    $pdo = db();

    if ($user['role'] === 'author') {
        // Authors see only their own posts
        $stmt = $pdo->prepare("
            SELECT bp.*, u.name AS author_name
            FROM blog_posts bp
            LEFT JOIN users u ON bp.author_id = u.id
            WHERE bp.author_id = ?
            ORDER BY bp.created_at DESC
        ");
        $stmt->execute([$user['id']]);
    } else {
        // Admins see all
        $stmt = $pdo->query("
            SELECT bp.*, u.name AS author_name
            FROM blog_posts bp
            LEFT JOIN users u ON bp.author_id = u.id
            ORDER BY bp.created_at DESC
        ");
    }

    $posts = $stmt->fetchAll();
    foreach ($posts as &$p) {
        $p['tags'] = array_filter(array_map('trim', explode(',', $p['tags'] ?? '')));
    }
    unset($p);

    jsonResponse(['success' => true, 'count' => count($posts), 'data' => $posts]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
