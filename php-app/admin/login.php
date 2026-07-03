<?php
/**
 * admin/login.php – Admin Dashboard Login
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

// Redirect if already logged in
if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in all fields';
    } else {
        try {
            $pdo = db();
            $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Ensure only admin or authors can access the dashboard
                if (in_array($user['role'], ['admin', 'author'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];

                    header('Location: ' . BASE_URL . '/admin/index.php');
                    exit;
                } else {
                    $error = 'You do not have access permissions for this portal.';
                }
            } else {
                $error = 'Invalid email or password';
            }
        } catch (Exception $e) {
            $error = 'System error occurred: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login – Prayogbharti Foundation</title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔒</text></svg>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="admin-body">
  <div class="admin-login-wrap">
    <div class="admin-login-card">
      <div class="logo">
        <div class="logo-icon" style="width:3rem;height:3rem;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
          <span style="color:#fff;font-weight:700;font-family:'Playfair Display',serif;">PB</span>
        </div>
        <h2>Admin Portal</h2>
        <div class="subtitle">Prayogbharti Foundation</div>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-danger" style="font-size:0.875rem;padding:0.75rem;border-radius:0.5rem;margin-bottom:1.25rem;">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label class="form-label" style="font-size:0.875rem;font-weight:700;margin-bottom:0.25rem;">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="admin@prayogbharti.org" required>
        </div>
        <div class="mb-4">
          <label class="form-label" style="font-size:0.875rem;font-weight:700;margin-bottom:0.25rem;">Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-submit" style="width:100%;">
          Sign In
        </button>
      </form>
    </div>
  </div>
</body>
</html>
