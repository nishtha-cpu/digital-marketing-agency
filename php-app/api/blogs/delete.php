<?php
/**
 * api/blogs/delete.php – DELETE /api/blogs/delete.php?id=X
 * Replaces: blogController.deleteBlogPost + DELETE /api/blogs/:id
 * Access: Private (Admin/Author – must own the post)
 */

require_once __DIR__ . '/../_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if (!in_array($_SERVER['REQUEST_METHOD'], ['DELETE', 'POST'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$user = requireRole('admin', 'author');
$id   = (int) ($_GET['id'] ?? 0);
if (!$id) jsonResponse(['success' => false, 'message' => 'Blog post ID is required'], 400);

try {
    $pdo  = db();
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if (!$post) jsonResponse(['success' => false, 'message' => 'Blog post not found'], 404);

    if ((int)$post['author_id'] !== (int)$user['id'] && $user['role'] !== 'admin') {
        jsonResponse(['success' => false, 'message' => 'Not authorized to delete this blog post'], 403);
    }

    $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true, 'message' => 'Blog post removed successfully']);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
