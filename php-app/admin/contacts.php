<?php
/**
 * admin/contacts.php - Manage Contact Enquiries
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

$pdo = db();
$msg = '';

// Handle status toggling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $contactId = (int)($_POST['contact_id'] ?? 0);
    if ($contactId) {
        if ($_POST['action'] === 'toggle_read') {
            $currentStatus = $_POST['current_status'] ?? 'Unread';
            $newStatus = ($currentStatus === 'Unread') ? 'Read' : 'Unread';
            $stmt = $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $contactId]);
            $msg = "Enquiry marked as " . strtolower($newStatus) . ".";
        } elseif ($_POST['action'] === 'toggle_star') {
            $currentStar = (int)($_POST['current_star'] ?? 0);
            $newStar = 1 - $currentStar;
            $stmt = $pdo->prepare("UPDATE contacts SET starred = ? WHERE id = ?");
            $stmt->execute([$newStar, $contactId]);
            $msg = $newStar ? "Enquiry starred." : "Enquiry unstarred.";
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
            $stmt->execute([$contactId]);
            $msg = "Enquiry deleted successfully.";
        }
    }
}

// Filters
$filterMode = $_GET['filter'] ?? 'All';
$searchQuery = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM contacts WHERE 1=1";
$params = [];

if ($filterMode === 'Unread') {
    $sql .= " AND status = 'Unread'";
} elseif ($filterMode === 'Starred') {
    $sql .= " AND starred = 1";
}

if ($searchQuery !== '') {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR message LIKE ? OR phone LIKE ?)";
    $likeQuery = '%' . $searchQuery . '%';
    $params = array_merge($params, [$likeQuery, $likeQuery, $likeQuery, $likeQuery]);
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$contacts = $stmt->fetchAll();

// General Stats
$totalEnquiries = (int) $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$unreadCount = (int) $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'Unread'")->fetchColumn();
$starredCount = (int) $pdo->query("SELECT COUNT(*) FROM contacts WHERE starred = 1")->fetchColumn();

renderAdminHeader('Contact Enquiries');
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.5rem;">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
  <div class="col-4">
    <div class="bg-card rounded-lg p-3 border border-border">
      <div class="text-xs text-muted" style="font-size:0.8rem;">Total Enquiries</div>
      <div class="text-2xl font-semibold mt-1" style="font-size:1.5rem; font-weight:600;"><?= $totalEnquiries ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="bg-card rounded-lg p-3 border border-border">
      <div class="text-xs text-muted" style="font-size:0.8rem;">Unread</div>
      <div class="text-2xl font-semibold mt-1 text-success" style="font-size:1.5rem; font-weight:600;"><?= $unreadCount ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="bg-card rounded-lg p-3 border border-border">
      <div class="text-xs text-muted" style="font-size:0.8rem;">Starred</div>
      <div class="text-2xl font-semibold mt-1 text-warning" style="font-size:1.5rem; font-weight:600;"><?= $starredCount ?></div>
    </div>
  </div>
</div>

<!-- Filters Toolbar -->
<div class="bg-card rounded-xl p-3 border border-border mb-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div class="d-flex gap-2">
      <?php foreach (['All', 'Unread', 'Starred'] as $mode): ?>
        <a href="?filter=<?= $mode ?>&search=<?= urlencode($searchQuery) ?>" 
           class="btn btn-sm px-3 py-2 rounded-lg text-decoration-none <?= $filterMode === $mode ? 'btn-success text-white' : 'btn-light text-muted' ?>" 
           style="font-size:0.875rem; font-weight: 500; <?= $filterMode === $mode ? 'background-color:#2E7D32;' : '' ?>">
          <?= $mode ?>
        </a>
      <?php endforeach; ?>
    </div>
    
    <form method="GET" action="" class="d-flex align-items-center position-relative">
      <input type="hidden" name="filter" value="<?= htmlspecialchars($filterMode) ?>">
      <i class="bi bi-search absolute-left position-absolute text-muted" style="left:12px; top:50%; transform:translateY(-50%);"></i>
      <input type="text" name="search" placeholder="Search enquiries..." value="<?= htmlspecialchars($searchQuery) ?>" 
             class="form-control form-control-sm pl-8" style="padding-left:36px; width:260px; font-size:0.875rem;">
    </form>
  </div>
</div>

<!-- Enquiries Cards List -->
<div class="space-y-3">
  <?php if (empty($contacts)): ?>
    <div class="bg-card rounded-xl p-5 border border-border text-center text-muted text-sm">
      No enquiries found.
    </div>
  <?php else: foreach ($contacts as $c): ?>
    <div class="bg-card rounded-xl p-4 border border-border hover:shadow-md transition-shadow mb-3" 
         style="<?= $c['status'] === 'Unread' ? 'border-left: 4px solid #2E7D32; background: rgba(46,125,50,0.02);' : '' ?>">
      <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <div class="d-flex align-items-start gap-3 flex-grow-1">
          <div class="w-10 h-10 rounded-full d-flex align-items-center justify-content-center text-white font-semibold flex-shrink-0" 
               style="width:40px; height:40px; border-radius:50%; background:#2E7D32;">
            <?= htmlspecialchars(strtoupper(substr($c['name'], 0, 1))) ?>
          </div>
          <div class="flex-grow-1 space-y-1">
            <div class="d-flex flex-wrap align-items-center gap-3">
              <h3 class="font-semibold text-dark h6 mb-0"><?= htmlspecialchars($c['name']) ?></h3>
              <span class="text-sm text-muted" style="font-size:0.85rem;"><?= htmlspecialchars($c['email']) ?></span>
              <span class="badge bg-light text-dark font-normal border"><?= htmlspecialchars($c['phone'] ?? 'No Phone') ?></span>
              <?php if ($c['status'] === 'Unread'): ?>
                <span class="badge bg-primary bg-opacity-10 text-primary">New</span>
              <?php endif; ?>
            </div>
            
            <div class="bg-light p-3 rounded-lg border border-light mt-2" style="font-size:0.875rem;">
              <p class="font-semibold text-xs text-muted mb-1 uppercase tracking-wider" style="font-size:0.7rem; font-weight:600;">Enquiry</p>
              <p class="mb-0 text-dark" style="white-space:pre-wrap;"><?= htmlspecialchars($c['message']) ?></p>
            </div>
            
            <div class="text-xs text-muted mt-2" style="font-size:0.75rem;">
              <i class="bi bi-clock me-1"></i> <?= date('F j, Y, g:i A', strtotime($c['created_at'])) ?>
            </div>
          </div>
        </div>
        
        <div class="d-flex align-items-center gap-2 align-self-end align-self-md-start">
          <!-- Star Toggle -->
          <form method="POST" action="" style="display:inline;">
            <input type="hidden" name="action" value="toggle_star">
            <input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
            <input type="hidden" name="current_star" value="<?= $c['starred'] ?>">
            <button type="submit" class="btn btn-sm btn-light border p-2" title="Star / Unstar">
              <i class="bi bi-star<?= $c['starred'] ? '-fill text-warning' : '' ?>"></i>
            </button>
          </form>

          <!-- Read Toggle -->
          <form method="POST" action="" style="display:inline;">
            <input type="hidden" name="action" value="toggle_read">
            <input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
            <input type="hidden" name="current_status" value="<?= $c['status'] ?>">
            <button type="submit" class="btn btn-sm btn-light border font-semibold px-2 py-2" style="font-size: 0.75rem;">
              <?= $c['status'] === 'Unread' ? 'Mark Read' : 'Mark Unread' ?>
            </button>
          </form>

          <!-- Delete -->
          <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this enquiry?')" style="display:inline;">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="contact_id" value="<?= $c['id'] ?>">
            <button type="submit" class="btn btn-sm btn-light border text-danger p-2" title="Delete">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; endif; ?>
</div>

<?php
renderAdminFooter();
?>
