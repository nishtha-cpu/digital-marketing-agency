<?php
/**
 * admin/users.php - Manage Users
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

requireRole('admin'); // Only admins can manage users

$pdo = db();
$msg = '';
$error = '';

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Name, email, and password are required.";
    } elseif (!in_array($role, ['admin', 'author', 'user'])) {
        $error = "Invalid role selected.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "User with this email already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashedPassword, $role])) {
                $msg = "User created successfully.";
            } else {
                $error = "Failed to create user.";
            }
        }
    }
}

// Handle Delete User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userId = (int)($_POST['user_id'] ?? 0);
    // Ensure we don't delete ourselves
    $currentUserId = currentUserId(); // From _helpers.php which auth_check.php requires
    
    if ($userId === $currentUserId) {
        $error = "You cannot delete your own account.";
    } elseif ($userId) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $msg = "User deleted successfully.";
    }
}

// Fetch Users
$stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

renderAdminHeader('Manage Users');
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

<div class="row">
  <div class="col-md-4 mb-4">
    <div class="admin-table-wrap p-4">
      <h5 class="mb-3">Add New User</h5>
      <form method="POST" action="">
        <input type="hidden" name="action" value="add">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control form-control-sm" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control form-control-sm" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control form-control-sm" required minlength="6">
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select form-select-sm">
            <option value="user">User</option>
            <option value="author">Author</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color:#2E7D32; border-color:#2E7D32;">Create User</button>
      </form>
    </div>
  </div>

  <div class="col-md-8">
    <div class="admin-table-wrap">
      <div class="admin-table-header">
        <h2>All Users</h2>
      </div>
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Created Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
              <tr><td colspan="5" class="text-center text-muted">No users found.</td></tr>
            <?php else: foreach ($users as $u): ?>
              <tr>
                <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge badge-<?= $u['role'] === 'admin' ? 'closed' : ($u['role'] === 'author' ? 'contacted' : 'new') ?>"><?= ucfirst($u['role']) ?></span></td>
                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                <td>
                  <?php if ($u['id'] != currentUserId()): ?>
                  <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this user?')" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <button type="submit" class="btn-sm btn-delete" style="padding:0.35rem 0.5rem;"><i class="bi bi-trash"></i></button>
                  </form>
                  <?php else: ?>
                  <span class="text-muted" style="font-size:0.75rem;">(You)</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
renderAdminFooter();
?>
