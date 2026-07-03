<?php
/**
 * admin/services.php - Manage Services
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

requireRole('admin'); // Restricted to system administrators

$pdo = db();
$msg = '';

// Handle Delete Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $serviceId = (int)($_POST['service_id'] ?? 0);
    if ($serviceId) {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$serviceId]);
        $msg = "Service removed successfully.";
    }
}

// Fetch all services
$stmt = $pdo->query("SELECT * FROM services ORDER BY name ASC");
$services = $stmt->fetchAll();

renderAdminHeader('Services Management');
?>

<?php if ($msg): ?>
  <div class="alert alert-info" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<div class="mb-4 text-end">
  <a href="service_edit.php" class="btn btn-primary btn-sm px-3 py-2">
    <i class="bi bi-plus-lg"></i> Add New Service
  </a>
</div>

<div class="admin-table-wrap">
  <div class="admin-table-header">
    <h2>All Services</h2>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Icon Identifier</th>
          <th>Price Model</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($services)): ?>
          <tr><td colspan="6" class="text-center text-muted">No services found.</td></tr>
        <?php else: foreach ($services as $s): ?>
          <tr>
            <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
            <td style="max-width:300px; white-space:normal;"><?= htmlspecialchars($s['description']) ?></td>
            <td><code><?= htmlspecialchars($s['icon']) ?></code></td>
            <td><?= htmlspecialchars($s['price']) ?></td>
            <td>
              <span class="badge badge-<?= $s['active'] ? 'active' : 'inactive' ?>">
                <?= $s['active'] ? 'Active' : 'Inactive' ?>
              </span>
            </td>
            <td>
              <div style="display:flex; gap:0.25rem;">
                <a href="service_edit.php?id=<?= $s['id'] ?>" class="btn-sm btn-edit"><i class="bi bi-pencil-fill"></i> Edit</a>
                
                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this service?')" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
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
