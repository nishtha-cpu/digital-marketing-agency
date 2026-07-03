<?php
/**
 * admin/blog_edit.php - Create/Edit Blog Post
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

$pdo = db();
$userId = currentUserId();
$role = currentRole();

$id = (int)($_GET['id'] ?? 0);
$post = null;
$error = '';
$success = '';

// Load existing post
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if (!$post) {
        header('Location: ' . BASE_URL . '/admin/blogs.php');
        exit;
    }

    // Authorization check: Only author of the post or admin can edit
    if ($role !== 'admin' && (int)$post['author_id'] !== $userId) {
        header('Location: ' . BASE_URL . '/admin/blogs.php');
        exit;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $coverImage = trim($_POST['cover_image'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $status = $_POST['status'] ?? 'draft';

    if (!$title || !$summary || !$content) {
        $error = 'Please fill in all required fields (Title, Summary, and Content).';
    } else {
        try {
            // Slug generation
            $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', preg_replace('/[^\w\s-]/u', '', strtolower($title))));
            $slug = trim($slug, '-');

            if ($id) {
                // Edit existing post
                $stmt = $pdo->prepare("
                    UPDATE blog_posts 
                    SET title = ?, slug = ?, summary = ?, content = ?, cover_image = ?, tags = ?, status = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$title, $slug, $summary, $content, $coverImage, $tags, $status, $id]);
                $success = 'Blog post updated successfully.';
                
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
                $stmt->execute([$id]);
                $post = $stmt->fetch();
            } else {
                // Create new post
                $stmt = $pdo->prepare("
                    INSERT INTO blog_posts (title, slug, summary, content, author_id, cover_image, tags, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $slug, $summary, $content, $userId, $coverImage, $tags, $status]);
                $id = $pdo->lastInsertId();
                
                header('Location: ' . BASE_URL . '/admin/blog_edit.php?id=' . $id . '&created=1');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['created']) && $_GET['created'] == 1) {
    $success = 'Blog post created successfully.';
}

renderAdminHeader($id ? 'Edit Blog Post' : 'Create Blog Post');
?>

<?php if ($error): ?>
  <div class="alert alert-danger" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<?php if ($success): ?>
  <div class="alert alert-success" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>

<div class="mb-4">
  <a href="blogs.php" class="btn btn-sm btn-edit"><i class="bi bi-arrow-left"></i> Back to List</a>
</div>

<div class="admin-form-card">
  <h2><?= $id ? 'Edit Post Details' : 'New Post Details' ?></h2>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label" style="font-weight:700;">Blog Title *</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="Breaking Barriers in STEM" required>
    </div>
    
    <div class="mb-3">
      <label class="form-label" style="font-weight:700;">Summary / Excerpt *</label>
      <textarea name="summary" class="form-control" rows="2" placeholder="Brief summary of the article..." required><?= htmlspecialchars($post['summary'] ?? '') ?></textarea>
    </div>
    
    <div class="mb-3">
      <label class="form-label" style="font-weight:700;">Article Content *</label>
      <textarea name="content" class="form-control" rows="8" placeholder="HTML or Markdown body contents..." required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label" style="font-weight:700;">Cover Image URL</label>
        <input type="url" name="cover_image" class="form-control" value="<?= htmlspecialchars($post['cover_image'] ?? '') ?>" placeholder="https://images.unsplash.com/...">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label" style="font-weight:700;">Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($post['tags'] ?? '') ?>" placeholder="Education, Impact, STEM">
      </div>
    </div>

    <div class="mb-4" style="max-width:200px;">
      <label class="form-label" style="font-weight:700;">Publishing Status</label>
      <select name="status" class="form-select">
        <option value="draft" <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
      </select>
    </div>

    <button type="submit" class="btn-submit">
      <?= $id ? 'Save Changes' : 'Create Post' ?>
    </button>
  </form>
</div>

<?php
renderAdminFooter();
?>
