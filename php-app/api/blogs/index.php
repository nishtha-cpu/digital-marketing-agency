<?php
/**
 * api/blogs/index.php
 * Replaces: blogController + blogRoutes.js
 *
 * GET  /api/blogs/index.php          – Public:        get all published blogs
 * POST /api/blogs/index.php          – Admin/Author:  create blog post
 */

require_once __DIR__ . '/../../api/_helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$pdo    = db();

/* ── GET: Published Blog Posts ─────────────────────────── */
if ($method === 'GET') {
    $stmt = $pdo->query("
        SELECT bp.*, u.name AS author_name
        FROM blog_posts bp
        LEFT JOIN users u ON bp.author_id = u.id
        WHERE bp.status = 'published'
        ORDER BY bp.created_at DESC
    ");
    $posts = $stmt->fetchAll();

    // Parse tags back into arrays for API consumers
    foreach ($posts as &$p) {
        $p['tags'] = array_filter(array_map('trim', explode(',', $p['tags'] ?? '')));
    }
    unset($p);

    jsonResponse(['success' => true, 'count' => count($posts), 'data' => $posts]);
}

/* ── POST: Create Blog Post ────────────────────────────── */
if ($method === 'POST') {
    $user = requireRole('admin', 'author');

    $body       = json_decode(file_get_contents('php://input'), true);
    $title      = trim($body['title']       ?? '');
    $summary    = trim($body['summary']     ?? '');
    $content    = trim($body['content']     ?? '');
    $coverImage = trim($body['coverImage']  ?? '');
    $tags       = is_array($body['tags'] ?? null)
                    ? implode(',', $body['tags'])
                    : trim($body['tags'] ?? '');
    $status     = $body['status'] ?? 'draft';

    if (!$title || !$summary || !$content) {
        jsonResponse(['success' => false, 'message' => 'Please enter all required fields: title, summary, and content'], 400);
    }

    // Check unique title
    $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE title = ?");
    $stmt->execute([$title]);
    if ($stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'A blog post with this title already exists'], 400);
    }

    // Generate slug
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', preg_replace('/[^\w\s-]/u', '', strtolower($title))));
    $slug = trim($slug, '-');

    // Ensure unique slug
    $baseSlug = $slug;
    $i = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE slug = ?");
        $stmt->execute([$slug]);
        if (!$stmt->fetch()) break;
        $slug = $baseSlug . '-' . $i++;
    }

    $validStatuses = ['draft', 'published'];
    if (!in_array($status, $validStatuses)) $status = 'draft';

    $stmt = $pdo->prepare("
        INSERT INTO blog_posts (title, slug, summary, content, author_id, cover_image, tags, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$title, $slug, $summary, $content, $user['id'], $coverImage, $tags, $status]);
    $id = $pdo->lastInsertId();

    jsonResponse([
        'success' => true,
        'message' => 'Blog post created successfully',
        'data'    => ['id' => $id, 'title' => $title, 'slug' => $slug, 'status' => $status]
    ], 201);
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
