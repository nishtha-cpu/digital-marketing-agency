<?php
/**
 * admin/blogs.php - Manage Blog Posts
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

$pdo = db();
$msg = '';
$userId = currentUserId();
$role = currentRole();

// Handle Delete Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $postId = (int)($_POST['post_id'] ?? 0);
    if ($postId) {
        // Fetch to verify ownership
        $stmt = $pdo->prepare("SELECT author_id FROM blog_posts WHERE id = ?");
        $stmt->execute([$postId]);
        $post = $stmt->fetch();
        
        if ($post) {
            if ($role === 'admin' || (int)$post['author_id'] === $userId) {
                $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
                $stmt->execute([$postId]);
                $msg = "Blog post deleted successfully.";
            } else {
                $msg = "Error: You are not authorized to delete this post.";
            }
        }
    }
}

// Fetch Blog Posts based on role
if ($role === 'author') {
    $stmt = $pdo->prepare("
        SELECT bp.*, u.name AS author_name 
        FROM blog_posts bp 
        LEFT JOIN users u ON bp.author_id = u.id 
        WHERE bp.author_id = ?
        ORDER BY bp.created_at DESC
    ");
    $stmt->execute([$userId]);
} else {
    $stmt = $pdo->query("
        SELECT bp.*, u.name AS author_name 
        FROM blog_posts bp 
        LEFT JOIN users u ON bp.author_id = u.id 
        ORDER BY bp.created_at DESC
    ");
}
$posts = $stmt->fetchAll();

renderAdminHeader('Blog Posts Management');
?>

<?php if ($msg): ?>
  <div class="alert alert-info" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<div class="mb-4 text-end">
  <a href="blog_edit.php" class="btn btn-primary btn-sm px-3 py-2">
    <i class="bi bi-plus-lg"></i> Create New Post
  </a>
</div>

<div class="admin-table-wrap">
  <div class="admin-table-header">
    <h2>All Blog Posts</h2>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Status</th>
          <th>Published Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($posts)): ?>
          <tr><td colspan="5" class="text-center text-muted">No blog posts found.</td></tr>
        <?php else: foreach ($posts as $p): ?>
          <tr>
            <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
            <td><?= htmlspecialchars($p['author_name'] ?? 'Unknown') ?></td>
            <td><span class="badge badge-<?= $p['status'] ?>"><?= $p['status'] ?></span></td>
            <td><?= date('F j, Y', strtotime($p['created_at'])) ?></td>
            <td>
              <div style="display:flex; gap:0.25rem;">
                <a href="blog_edit.php?id=<?= $p['id'] ?>" class="btn-sm btn-edit"><i class="bi bi-pencil-fill"></i> Edit</a>
                
                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this blog post?')" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn-sm btn-delete"><i class="bi bi-trash"></i> Delete</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
renderAdminFooter();
?>
