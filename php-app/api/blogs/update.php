<?php
/**
 * api/blogs/update.php – PUT /api/blogs/update.php?id=X
 * Replaces: blogController.updateBlogPost + PUT /api/blogs/:id
 * Access: Private (Admin/Author – must own the post or be admin)
 */

require_once __DIR__ . '/../../api/_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'POST'])) {
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

    // Ownership check
    if ((int)$post['author_id'] !== (int)$user['id'] && $user['role'] !== 'admin') {
        jsonResponse(['success' => false, 'message' => 'Not authorized to update this blog post'], 403);
    }

    $body       = json_decode(file_get_contents('php://input'), true);
    $title      = isset($body['title'])       ? trim($body['title'])       : $post['title'];
    $summary    = isset($body['summary'])     ? trim($body['summary'])     : $post['summary'];
    $content    = isset($body['content'])     ? trim($body['content'])     : $post['content'];
    $coverImage = isset($body['coverImage'])  ? trim($body['coverImage'])  : $post['cover_image'];
    $tags       = isset($body['tags'])
                    ? (is_array($body['tags']) ? implode(',', $body['tags']) : trim($body['tags']))
                    : $post['tags'];
    $status     = isset($body['status']) && in_array($body['status'], ['draft','published'])
                    ? $body['status']
                    : $post['status'];

    $pdo->prepare("
        UPDATE blog_posts
        SET title=?, summary=?, content=?, cover_image=?, tags=?, status=?
        WHERE id=?
    ")->execute([$title, $summary, $content, $coverImage, $tags, $status, $id]);

    jsonResponse([
        'success' => true,
        'message' => 'Blog post updated successfully',
        'data'    => ['id' => $id, 'title' => $title, 'status' => $status]
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}
