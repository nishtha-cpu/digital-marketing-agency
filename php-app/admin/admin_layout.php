<?php
/**
 * admin/admin_layout.php – Sidebar and Layout Helper
 * Converted to match the React Admin Dashboard (Forest Green #2E7D32 design)
 */

require_once __DIR__ . '/../includes/auth_check.php';

function renderAdminHeader(string $title): void {
    $currentUrl = $_SERVER['REQUEST_URI'];
    $role = currentRole();
    $name = currentName();
    
    // Check active states
    $isDashboardActive = (strpos($currentUrl, 'index.php') !== false || basename($currentUrl) === 'admin');
    $isLeadsActive = (strpos($currentUrl, 'leads.php') !== false);
    $isContactsActive = (strpos($currentUrl, 'contacts.php') !== false);
    $isBlogsActive = (strpos($currentUrl, 'blog') !== false);
    $isServicesActive = (strpos($currentUrl, 'service') !== false);
    $isSubscribersActive = (strpos($currentUrl, 'subscriber') !== false);
    $isUsersActive = (strpos($currentUrl, 'user') !== false);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?= htmlspecialchars($title) ?> – PrayogBharti Admin</title>
      <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
      <!-- Bootstrap 5 -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
      <style>
        /* Custom UI override matching React Admin Layout */
        body.admin-body {
          background-color: #f8fafc;
          color: #0f172a;
        }
        .admin-sidebar {
          width: 256px;
          background: #ffffff !important;
          border-right: 1px solid rgba(0, 0, 0, 0.08);
          color: #334155 !important;
          padding: 0 !important;
        }
        .admin-sidebar .brand {
          height: 64px;
          border-bottom: 1px solid rgba(0, 0, 0, 0.08);
          display: flex;
          align-items: center;
          padding: 0 1.25rem;
          margin-bottom: 1rem;
        }
        .admin-sidebar .brand-icon {
          width: 32px;
          height: 32px;
          background: #2E7D32 !important;
          border-radius: 8px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: #ffffff;
          font-weight: 700;
        }
        .admin-sidebar .brand-name {
          color: #0f172a;
          font-weight: 600;
          font-size: 1.125rem;
          margin-left: 0.5rem;
        }
        .admin-nav {
          padding: 0 0.75rem;
        }
        .admin-nav a {
          color: #64748b !important;
          font-weight: 500;
          border-radius: 8px;
          padding: 0.625rem 0.875rem !important;
          margin-bottom: 0.25rem;
          transition: all 0.2s;
        }
        .admin-nav a:hover {
          background: #f1f5f9 !important;
          color: #0f172a !important;
        }
        .admin-nav a.active {
          background: #2E7D32 !important;
          color: #ffffff !important;
        }
        .admin-nav a.active .nav-icon {
          color: #ffffff !important;
        }
        .admin-main {
          margin-left: 256px;
          padding: 0;
        }
        .admin-header-nav {
          height: 64px;
          background: #ffffff;
          border-bottom: 1px solid rgba(0, 0, 0, 0.08);
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 0 1.5rem;
          position: sticky;
          top: 0;
          z-index: 30;
        }
        .admin-breadcrumb {
          display: flex;
          align-items: center;
          gap: 0.5rem;
          font-size: 0.875rem;
          color: #64748b;
        }
        .admin-breadcrumb a {
          color: #64748b;
        }
        .admin-header-right {
          display: flex;
          align-items: center;
          gap: 1.25rem;
        }
        .admin-profile-trigger {
          display: flex;
          align-items: center;
          gap: 0.75rem;
          padding: 0.375rem 0.75rem;
          border-radius: 8px;
          background: transparent;
          border: none;
          transition: background 0.2s;
        }
        .admin-profile-trigger:hover {
          background: #f1f5f9;
        }
        .admin-avatar {
          width: 32px;
          height: 32px;
          border-radius: 50%;
          background: #2E7D32;
          color: #ffffff;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 600;
          font-size: 0.875rem;
        }
        .admin-page-content {
          padding: 1.5rem;
        }
        .admin-btn-logout {
          background: #fee2e2;
          color: #dc2626;
          border: none;
          padding: 0.5rem 1rem;
          border-radius: 8px;
          font-size: 0.875rem;
          font-weight: 600;
          display: inline-flex;
          align-items: center;
          gap: 0.5rem;
          transition: background 0.2s;
        }
        .admin-btn-logout:hover {
          background: #fca5a5;
        }
        .admin-nav .logout-link {
          color: #ef4444 !important;
          margin-top: 2rem;
        }
        .admin-nav .logout-link:hover {
          background: #fef2f2 !important;
        }
      </style>
    </head>
    <body class="admin-body">

      <!-- SIDEBAR -->
      <aside class="admin-sidebar">
        <div class="brand">
          <div class="brand-icon">
            <i class="bi bi-mortarboard-fill" style="font-size:1.1rem; color:#fff;"></i>
          </div>
          <span class="brand-name">PrayogBharti</span>
        </div>
        <ul class="admin-nav">
          <li>
            <a href="<?= BASE_URL ?>/admin/index.php" class="<?= $isDashboardActive ? 'active' : '' ?>">
              <i class="bi bi-speedometer2 nav-icon me-2"></i> Dashboard
            </a>
          </li>
          <li>
            <a href="<?= BASE_URL ?>/admin/leads.php" class="<?= $isLeadsActive ? 'active' : '' ?>">
              <i class="bi bi-person-check nav-icon me-2"></i> Leads
            </a>
          </li>
          <li>
            <a href="<?= BASE_URL ?>/admin/contacts.php" class="<?= $isContactsActive ? 'active' : '' ?>">
              <i class="bi bi-envelope nav-icon me-2"></i> Contact Enquiries
            </a>
          </li>
          <li>
            <a href="<?= BASE_URL ?>/admin/subscribers.php" class="<?= $isSubscribersActive ? 'active' : '' ?>">
              <i class="bi bi-file-text nav-icon me-2"></i> Newsletter
            </a>
          </li>
          <?php if ($role === 'admin'): ?>
          <li>
            <a href="<?= BASE_URL ?>/admin/services.php" class="<?= $isServicesActive ? 'active' : '' ?>">
              <i class="bi bi-briefcase nav-icon me-2"></i> Manage Services
            </a>
          </li>
          <li>
            <a href="<?= BASE_URL ?>/admin/users.php" class="<?= $isUsersActive ? 'active' : '' ?>">
              <i class="bi bi-people nav-icon me-2"></i> Manage Users
            </a>
          </li>
          <?php endif; ?>
          <li>
            <a href="<?= BASE_URL ?>/admin/blogs.php" class="<?= $isBlogsActive ? 'active' : '' ?>">
              <i class="bi bi-book nav-icon me-2"></i> Manage Blogs
            </a>
          </li>
          <li>
            <a href="<?= BASE_URL ?>/admin/logout.php" class="logout-link">
              <i class="bi bi-box-arrow-right nav-icon me-2"></i> Sign Out
            </a>
          </li>
        </ul>
      </aside>

      <!-- MAIN CONTENT WRAP -->
      <div class="admin-main">
        <!-- Top Navigation -->
        <header class="admin-header-nav">
          <div class="admin-breadcrumb">
            <i class="bi bi-house-door-fill text-muted"></i>
            <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
            <span class="text-dark font-medium"><?= htmlspecialchars($title) ?></span>
          </div>

          <div class="admin-header-right">
            <!-- Profile Info -->
            <div class="admin-profile-trigger">
              <div class="admin-avatar">
                <?= htmlspecialchars(strtoupper(substr($name, 0, 1))) ?>
              </div>
              <div class="text-left d-none d-lg-block" style="line-height: 1.2;">
                <div class="text-sm font-medium text-dark" style="font-size:0.875rem; font-weight: 500;"><?= htmlspecialchars($name) ?></div>
                <div class="text-xs text-muted" style="font-size:0.75rem;"><?= htmlspecialchars(ucfirst($role)) ?></div>
              </div>
            </div>

            <!-- Logout -->
            <a href="<?= BASE_URL ?>/admin/logout.php" class="admin-btn-logout">
              <i class="bi bi-box-arrow-right"></i> Log Out
            </a>
          </div>
        </header>

        <!-- Main Page View Wrapper -->
        <div class="admin-page-content">
    <?php
}

function renderAdminFooter(): void {
    ?>
        </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
