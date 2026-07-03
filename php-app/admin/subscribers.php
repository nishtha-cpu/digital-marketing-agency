<?php
/**
 * admin/subscribers.php - Manage Newsletter Subscribers
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

requireRole('admin');

$pdo = db();
$msg = '';
$error = '';

// Handle Actions (Toggle, Delete, Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $subId = (int)($_POST['subscriber_id'] ?? 0);
    
    if ($_POST['action'] === 'toggle' && $subId) {
        $stmt = $pdo->prepare("SELECT status FROM newsletter_subscribers WHERE id = ?");
        $stmt->execute([$subId]);
        $status = $stmt->fetchColumn();
        $newStatus = ($status === 'Active') ? 'Inactive' : 'Active';
        
        $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $subId]);
        $msg = "Subscriber status updated successfully.";
    } elseif ($_POST['action'] === 'delete' && $subId) {
        $stmt = $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
        $stmt->execute([$subId]);
        $msg = "Subscriber deleted successfully.";
    } elseif ($_POST['action'] === 'save_edit') {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $name = trim($_POST['name'] ?? '');
        $status = $_POST['status'] ?? 'Active';
        
        if (!$email) {
            $error = "Email address is required.";
        } else {
            if ($subId) {
                // Update
                $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET name = ?, email = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $email, $status, $subId]);
                $msg = "Subscriber updated successfully.";
            } else {
                // Add new
                $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (name, email, status) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $status]);
                $msg = "Subscriber added successfully.";
            }
        }
    }
}

// Fetch single subscriber for edit modal if requested
$editingSub = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM newsletter_subscribers WHERE id = ?");
    $stmt->execute([$editId]);
    $editingSub = $stmt->fetch();
}

// Filters & Search
$searchQuery = trim($_GET['search'] ?? '');
$sql = "SELECT * FROM newsletter_subscribers WHERE 1=1";
$params = [];

if ($searchQuery !== '') {
    $sql .= " AND (name LIKE ? OR email LIKE ?)";
    $likeQuery = '%' . $searchQuery . '%';
    $params = [$likeQuery, $likeQuery];
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$subscribers = $stmt->fetchAll();

// Stats
$totalSubscribers = (int) $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers")->fetchColumn();
$activeCount = (int) $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'Active'")->fetchColumn();
$inactiveCount = $totalSubscribers - $activeCount;

renderAdminHeader('Newsletter Subscribers');
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<!-- Stats Grid -->
<div class="row g-3 mb-4">
  <div class="col-4">
    <div class="bg-card rounded-lg p-3 border border-border">
      <div class="text-xs text-muted" style="font-size:0.8rem;">Total Subscribers</div>
      <div class="text-2xl font-semibold mt-1" style="font-size:1.5rem; font-weight:600;"><?= $totalSubscribers ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="bg-card rounded-lg p-3 border border-border">
      <div class="text-xs text-muted" style="font-size:0.8rem;">Active</div>
      <div class="text-2xl font-semibold mt-1 text-success" style="font-size:1.5rem; font-weight:600;"><?= $activeCount ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="bg-card rounded-lg p-3 border border-border">
      <div class="text-xs text-muted" style="font-size:0.8rem;">Inactive</div>
      <div class="text-2xl font-semibold mt-1 text-danger" style="font-size:1.5rem; font-weight:600;"><?= $inactiveCount ?></div>
    </div>
  </div>
</div>

<!-- Toolbar -->
<div class="bg-card rounded-xl p-3 border border-border mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <form method="GET" action="" class="d-flex align-items-center position-relative">
    <i class="bi bi-search absolute-left position-absolute text-muted" style="left:12px; top:50%; transform:translateY(-50%);"></i>
    <input type="text" name="search" placeholder="Search subscribers..." value="<?= htmlspecialchars($searchQuery) ?>" 
           class="form-control form-control-sm pl-8" style="padding-left:36px; width:280px; font-size:0.875rem;">
  </form>
  
  <a href="?add=1" class="btn btn-sm btn-success px-3 py-2" style="background-color:#2E7D32;"><i class="bi bi-plus-lg"></i> Add Subscriber</a>
</div>

<!-- Add/Edit Modal (conditional rendering in page flow) -->
<?php if ($editingSub || isset($_GET['add'])): ?>
<div class="bg-card rounded-xl border border-border p-4 mb-4" style="max-width: 500px; background: #ffffff;">
  <h3 class="h6 font-semibold mb-3 text-dark"><?= $editingSub ? 'Edit Subscriber' : 'Add Subscriber' ?></h3>
  <form method="POST" action="?">
    <input type="hidden" name="action" value="save_edit">
    <?php if ($editingSub): ?>
      <input type="hidden" name="subscriber_id" value="<?= $editingSub['id'] ?>">
    <?php endif; ?>
    
    <div class="mb-3">
      <label class="form-label" style="font-size:0.8rem; font-weight:600;">Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editingSub['name'] ?? '') ?>" placeholder="John Doe">
    </div>
    <div class="mb-3">
      <label class="form-label" style="font-size:0.8rem; font-weight:600;">Email *</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editingSub['email'] ?? '') ?>" placeholder="john@example.com" required>
    </div>
    <div class="mb-3">
      <label class="form-label" style="font-size:0.8rem; font-weight:600;">Status</label>
      <select name="status" class="form-select">
        <option value="Active" <?= ($editingSub['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
        <option value="Inactive" <?= ($editingSub['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
    <div class="d-flex gap-2 justify-content-end">
      <a href="subscribers.php" class="btn btn-sm btn-light border">Cancel</a>
      <button type="submit" class="btn btn-sm btn-success" style="background-color:#2E7D32;">Save Changes</button>
    </div>
  </form>
</div>
<?php endif; ?>

<!-- Subscribers Table Card -->
<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Subscriber Name</th>
        <th>Email</th>
        <th>Subscribed Date</th>
        <th>Status</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($subscribers)): ?>
        <tr>
          <td colSpan="5" class="text-center text-muted py-4">No subscribers found.</td>
        </tr>
      <?php else: foreach ($subscribers as $s): ?>
        <tr>
          <td><strong><?= htmlspecialchars($s['name'] ?: 'N/A') ?></strong></td>
          <td><?= htmlspecialchars($s['email']) ?></td>
          <td><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
          <td>
            <form method="POST" action="" style="display:inline;">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="subscriber_id" value="<?= $s['id'] ?>">
              <button type="submit" class="badge btn border-0 py-1 px-2.5 transition-all <?= $s['status'] === 'Active' ? 'badge-active' : 'badge-inactive' ?>" style="font-size: 0.75rem;">
                <?= $s['status'] ?>
              </button>
            </form>
          </td>
          <td class="text-end">
            <div class="d-inline-flex gap-2">
              <a href="?edit=<?= $s['id'] ?>" class="btn-sm btn-edit" title="Edit subscriber">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this subscriber?')" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="subscriber_id" value="<?= $s['id'] ?>">
                <button type="submit" class="btn-sm btn-delete" title="Delete subscriber">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php
renderAdminFooter();
?>
