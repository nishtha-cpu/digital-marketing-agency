<?php
/**
 * admin/index.php - Admin Dashboard Overview
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

$pdo = db();

// Fetch metric aggregates
$totalLeads = (int) $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
$totalContacts = (int) $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$totalSubscribers = (int) $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE status = 'Active'")->fetchColumn();
$totalBlogs = (int) $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
$totalServices = (int) $pdo->query("SELECT COUNT(*) FROM services WHERE active = 1")->fetchColumn();

// Compile Recent Activities (Leads, Contacts, and Newsletter signups sorted by date)
$activities = [];

$recentLeads = $pdo->query("SELECT name, created_at, 'lead' AS type, service_interest FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();
foreach ($recentLeads as $l) {
    $activities[] = [
        'title' => 'Lead Submitted',
        'desc' => htmlspecialchars($l['name']) . ' interested in ' . htmlspecialchars($l['service_interest']),
        'time' => date('M j, Y g:i A', strtotime($l['created_at'])),
        'timestamp' => strtotime($l['created_at']),
        'type' => 'lead'
    ];
}

$recentContacts = $pdo->query("SELECT name, created_at, 'contact' AS type, message FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
foreach ($recentContacts as $c) {
    $activities[] = [
        'title' => 'Contact Enquiry',
        'desc' => 'Message from ' . htmlspecialchars($c['name']) . ': "' . htmlspecialchars(substr($c['message'], 0, 40)) . (strlen($c['message']) > 40 ? '...' : '') . '"',
        'time' => date('M j, Y g:i A', strtotime($c['created_at'])),
        'timestamp' => strtotime($c['created_at']),
        'type' => 'contact'
    ];
}

$recentSubs = $pdo->query("SELECT email, created_at, 'subscriber' AS type FROM newsletter_subscribers ORDER BY created_at DESC LIMIT 5")->fetchAll();
foreach ($recentSubs as $s) {
    $activities[] = [
        'title' => 'Newsletter Subscription',
        'desc' => 'New email subscribed: ' . htmlspecialchars($s['email']),
        'time' => date('M j, Y g:i A', strtotime($s['created_at'])),
        'timestamp' => strtotime($s['created_at']),
        'type' => 'subscriber'
    ];
}

// Sort activities by timestamp desc
usort($activities, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});
$activities = array_slice($activities, 0, 5);

renderAdminHeader('Dashboard');
?>

<!-- Welcome Banner -->
<div class="mb-4">
  <h2 class="h3 text-dark font-semibold">Dashboard Overview</h2>
  <p class="text-muted" style="font-size:0.875rem;">Welcome back! Here's what's happening with your organization.</p>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-4">
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="bg-card rounded-xl p-4 border border-border shadow-sm d-flex justify-content-between align-items-start">
      <div>
        <p class="text-sm text-muted mb-1" style="font-size: 0.85rem;">Total Leads</p>
        <h3 class="text-2xl font-semibold mb-2 text-dark" style="font-size: 1.75rem; font-weight: 600;"><?= $totalLeads ?></h3>
        <span class="text-xs text-success" style="font-size: 0.75rem;"><i class="bi bi-arrow-up-right"></i> Active submissions</span>
      </div>
      <div class="w-12 h-12 rounded-lg bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
        <i class="bi bi-person-check text-success" style="font-size: 1.5rem;"></i>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="bg-card rounded-xl p-4 border border-border shadow-sm d-flex justify-content-between align-items-start">
      <div>
        <p class="text-sm text-muted mb-1" style="font-size: 0.85rem;">Contact Enquiries</p>
        <h3 class="text-2xl font-semibold mb-2 text-dark" style="font-size: 1.75rem; font-weight: 600;"><?= $totalContacts ?></h3>
        <span class="text-xs text-primary" style="font-size: 0.75rem;"><i class="bi bi-envelope"></i> Message box</span>
      </div>
      <div class="w-12 h-12 rounded-lg bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
        <i class="bi bi-envelope text-primary" style="font-size: 1.5rem;"></i>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="bg-card rounded-xl p-4 border border-border shadow-sm d-flex justify-content-between align-items-start">
      <div>
        <p class="text-sm text-muted mb-1" style="font-size: 0.85rem;">Subscribers</p>
        <h3 class="text-2xl font-semibold mb-2 text-dark" style="font-size: 1.75rem; font-weight: 600;"><?= $totalSubscribers ?></h3>
        <span class="text-xs text-info" style="font-size: 0.75rem;"><i class="bi bi-people"></i> Active members</span>
      </div>
      <div class="w-12 h-12 rounded-lg bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
        <i class="bi bi-people text-info" style="font-size: 1.5rem;"></i>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3">
    <div class="bg-card rounded-xl p-4 border border-border shadow-sm d-flex justify-content-between align-items-start">
      <div>
        <p class="text-sm text-muted mb-1" style="font-size: 0.85rem;">Active Services</p>
        <h3 class="text-2xl font-semibold mb-2 text-dark" style="font-size: 1.75rem; font-weight: 600;"><?= $totalServices ?></h3>
        <span class="text-xs text-warning" style="font-size: 0.75rem;"><i class="bi bi-briefcase"></i> Service catalog</span>
      </div>
      <div class="w-12 h-12 rounded-lg bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
        <i class="bi bi-briefcase text-warning" style="font-size: 1.5rem;"></i>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <!-- Recent Activity -->
  <div class="col-12 col-lg-7">
    <div class="bg-card rounded-xl p-4 border border-border shadow-sm h-100">
      <h3 class="font-semibold text-dark mb-4 h5">Recent Activity</h3>
      <div class="space-y-3">
        <?php if (empty($activities)): ?>
          <div class="text-center py-5 text-sm text-muted">
            No recent activity yet. Submissions and signups will show up here.
          </div>
        <?php else: foreach ($activities as $act):
          $iconClass = 'bi-chat-left-text text-primary';
          $bgClass = 'bg-primary';
          if ($act['type'] === 'lead') {
              $iconClass = 'bi-person-check text-success';
              $bgClass = 'bg-success';
          } elseif ($act['type'] === 'subscriber') {
              $iconClass = 'bi-envelope-paper text-info';
              $bgClass = 'bg-info';
          }
        ?>
          <div class="d-flex align-items-start gap-3 pb-3 border-b border-border last:border-0 last:pb-0" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
            <div class="w-10 h-10 rounded-full bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0 <?= $bgClass ?>" style="width: 40px; height: 40px; border-radius: 50%;">
              <i class="bi <?= $iconClass ?>" style="font-size: 1.2rem;"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h4 class="font-medium text-dark text-sm mb-1" style="font-weight: 500; font-size:0.875rem;"><?= htmlspecialchars($act['title']) ?></h4>
              <p class="text-xs text-muted mb-0" style="font-size:0.75rem;"><?= $act['desc'] ?></p>
            </div>
            <span class="text-xs text-muted flex-shrink-0" style="font-size:0.75rem;"><?= $act['time'] ?></span>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="col-12 col-lg-5">
    <div class="bg-card rounded-xl p-4 border border-border shadow-sm h-100">
      <h3 class="font-semibold text-dark mb-4 h5">Quick Actions</h3>
      <div class="row g-3">
        <div class="col-6">
          <a href="leads.php" class="d-flex flex-col align-items-center text-center p-3 rounded-xl border border-border hover:border-success transition-all bg-light text-decoration-none h-100 justify-content-center">
            <div class="w-10 h-10 rounded-lg bg-success bg-opacity-10 d-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; border-radius: 8px;">
              <i class="bi bi-person-check text-success" style="font-size: 1.25rem;"></i>
            </div>
            <span class="font-medium text-dark text-sm" style="font-size:0.8rem; font-weight: 500;">Manage Leads</span>
          </a>
        </div>
        <div class="col-6">
          <a href="contacts.php" class="d-flex flex-col align-items-center text-center p-3 rounded-xl border border-border hover:border-success transition-all bg-light text-decoration-none h-100 justify-content-center">
            <div class="w-10 h-10 rounded-lg bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; border-radius: 8px;">
              <i class="bi bi-envelope text-primary" style="font-size: 1.25rem;"></i>
            </div>
            <span class="font-medium text-dark text-sm" style="font-size:0.8rem; font-weight: 500;">Enquiries</span>
          </a>
        </div>
        <div class="col-6">
          <a href="subscribers.php" class="d-flex flex-col align-items-center text-center p-3 rounded-xl border border-border hover:border-success transition-all bg-light text-decoration-none h-100 justify-content-center">
            <div class="w-10 h-10 rounded-lg bg-info bg-opacity-10 d-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; border-radius: 8px;">
              <i class="bi bi-file-text text-info" style="font-size: 1.25rem;"></i>
            </div>
            <span class="font-medium text-dark text-sm" style="font-size:0.8rem; font-weight: 500;">Newsletter</span>
          </a>
        </div>
        <div class="col-6">
          <a href="blogs.php" class="d-flex flex-col align-items-center text-center p-3 rounded-xl border border-border hover:border-success transition-all bg-light text-decoration-none h-100 justify-content-center">
            <div class="w-10 h-10 rounded-lg bg-warning bg-opacity-10 d-flex align-items-center justify-content-center mb-2" style="width:40px; height:40px; border-radius: 8px;">
              <i class="bi bi-book text-warning" style="font-size: 1.25rem;"></i>
            </div>
            <span class="font-medium text-dark text-sm" style="font-size:0.8rem; font-weight: 500;">Manage Blogs</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
renderAdminFooter();
?>
