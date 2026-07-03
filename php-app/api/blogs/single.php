<?php
/**
 * api/blogs/single.php – GET /api/blogs/single.php?slug=X
 * Replaces: blogController.getBlogPostBySlug + GET /api/blogs/:slug
 * Access: Public
 */

require_once __DIR__ . '/../../api/_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$slug = trim($_GET['slug'] ?? '');
if (!$slug) {
    jsonResponse(['success' => false, 'message' => 'Blog slug is required'], 400);
}

try {
    $pdo  = db();
    $stmt = $pdo->prepare("
        SELECT bp.*, u.name AS author_name
        FROM blog_posts bp
        LEFT JOIN users u ON bp.author_id = u.id
        WHERE bp.slug = ? AND bp.status = 'published'
    ");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();

    if (!$post) {
        jsonResponse(['success' => false, 'message' => 'Blog post not found or is in draft'], 404);
    }

    $post['tags'] = array_filter(array_map('trim', explode(',', $post['tags'] ?? '')));
    jsonResponse(['success' => true, 'data' => $post]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
