<?php
/**
 * admin/leads.php - Manage Leads
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

requireRole('admin'); // Only admins can manage leads

$pdo = db();
$msg = '';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $leadId = (int)($_POST['lead_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($leadId && in_array($status, ['new', 'contacted', 'closed'])) {
        $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
        $stmt->execute([$status, $leadId]);
        $msg = "Lead status updated successfully.";
    }
}

// Handle Delete Lead
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $leadId = (int)($_POST['lead_id'] ?? 0);
    if ($leadId) {
        $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$leadId]);
        $msg = "Lead deleted successfully.";
    }
}

// Fetch Leads
$stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
$leads = $stmt->fetchAll();

renderAdminHeader('Leads & Form Submissions');
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<div class="admin-table-wrap">
  <div class="admin-table-header">
    <h2>All Leads</h2>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Interest</th>
          <th>Message</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leads)): ?>
          <tr><td colspan="7" class="text-center text-muted">No leads found.</td></tr>
        <?php else: foreach ($leads as $l): ?>
          <tr>
            <td><strong><?= htmlspecialchars($l['name']) ?></strong></td>
            <td><?= htmlspecialchars($l['email']) ?></td>
            <td><?= htmlspecialchars($l['phone'] ?? '-') ?></td>
            <td><?= htmlspecialchars($l['service_interest']) ?></td>
            <td style="max-width:240px; white-space:normal; font-size:0.8rem;"><?= htmlspecialchars($l['message']) ?></td>
            <td><span class="badge badge-<?= $l['status'] ?>"><?= $l['status'] ?></span></td>
            <td>
              <div style="display:flex; gap:0.25rem; align-items:center;">
                <!-- Status Edit Form -->
                <form method="POST" action="" style="display:inline-flex;">
                  <input type="hidden" name="action" value="update_status">
                  <input type="hidden" name="lead_id" value="<?= $l['id'] ?>">
                  <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="width:110px; font-size:0.75rem; padding:0.25rem;">
                    <option value="new" <?= $l['status'] === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="contacted" <?= $l['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                    <option value="closed" <?= $l['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                  </select>
                </form>

                <!-- Delete Form -->
                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this lead?')" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="lead_id" value="<?= $l['id'] ?>">
                  <button type="submit" class="btn-sm btn-delete" style="padding:0.35rem 0.5rem;"><i class="bi bi-trash"></i></button>
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
