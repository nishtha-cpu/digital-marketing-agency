<?php
/**
 * admin/service_edit.php - Create/Edit Service
 */

require_once __DIR__ . '/admin_layout.php';
require_once __DIR__ . '/../config/db.php';

requireRole('admin');

$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$service = null;
$error = '';
$success = '';

// Load existing service
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();

    if (!$service) {
        header('Location: ' . BASE_URL . '/admin/services.php');
        exit;
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'marketing');
    $price = trim($_POST['price'] ?? 'Custom Pricing');
    $active = isset($_POST['active']) ? (int)$_POST['active'] : 1;
    $featuresRaw = trim($_POST['features'] ?? '');

    if (!$name || !$description) {
        $error = 'Please fill in all required fields (Name and Description).';
    } else {
        try {
            // Process features to JSON
            $featuresArr = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $featuresRaw))));
            $featuresJson = json_encode(array_values($featuresArr));

            if ($id) {
                // Edit existing
                $stmt = $pdo->prepare("
                    UPDATE services 
                    SET name = ?, description = ?, icon = ?, features = ?, price = ?, active = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$name, $description, $icon, $featuresJson, $price, $active, $id]);
                $success = 'Service updated successfully.';
                
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
                $stmt->execute([$id]);
                $service = $stmt->fetch();
            } else {
                // Create new
                $stmt = $pdo->prepare("
                    INSERT INTO services (name, description, icon, features, price, active) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $description, $icon, $featuresJson, $price, $active]);
                $id = $pdo->lastInsertId();
                
                header('Location: ' . BASE_URL . '/admin/service_edit.php?id=' . $id . '&created=1');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['created']) && $_GET['created'] == 1) {
    $success = 'Service created successfully.';
}

// Format JSON features back to textarea newline separated text
$featuresText = '';
if ($service && !empty($service['features'])) {
    $decoded = json_decode($service['features'], true);
    if (is_array($decoded)) {
        $featuresText = implode("\n", $decoded);
    }
}

renderAdminHeader($id ? 'Edit Service' : 'Add New Service');
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
  <a href="services.php" class="btn btn-sm btn-edit"><i class="bi bi-arrow-left"></i> Back to List</a>
</div>

<div class="admin-form-card">
  <h2><?= $id ? 'Edit Service Details' : 'New Service Details' ?></h2>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label" style="font-weight:700;">Service Name *</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($service['name'] ?? '') ?>" placeholder="STEM Tutoring" required>
    </div>
    
    <div class="mb-3">
      <label class="form-label" style="font-weight:700;">Description *</label>
      <textarea name="description" class="form-control" rows="4" placeholder="Describe the service offerings..." required><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label" style="font-weight:700;">Icon Identifier</label>
        <select name="icon" class="form-select">
          <option value="Award" <?= ($service['icon'] ?? '') === 'Award' ? 'selected' : '' ?>>Award (Scholarship)</option>
          <option value="BookOpen" <?= ($service['icon'] ?? '') === 'BookOpen' ? 'selected' : '' ?>>Book Open (Coaching)</option>
          <option value="Users" <?= ($service['icon'] ?? '') === 'Users' ? 'selected' : '' ?>>Users (Mentorship)</option>
          <option value="Globe" <?= ($service['icon'] ?? '') === 'Globe' ? 'selected' : '' ?>>Globe (Community)</option>
          <option value="marketing" <?= ($service['icon'] ?? '') === 'marketing' ? 'selected' : '' ?>>Marketing / General</option>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label" style="font-weight:700;">Price Model</label>
        <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($service['price'] ?? 'Custom Pricing') ?>" placeholder="Free / Custom Pricing">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label" style="font-weight:700;">Key Features (one per line)</label>
      <textarea name="features" class="form-control" rows="4" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"><?= htmlspecialchars($featuresText) ?></textarea>
    </div>

    <div class="mb-4" style="max-width:200px;">
      <label class="form-label" style="font-weight:700;">Service Status</label>
      <select name="active" class="form-select">
        <option value="1" <?= ($service['active'] ?? 1) == 1 ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= ($service['active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>

    <button type="submit" class="btn-submit">
      <?= $id ? 'Save Changes' : 'Create Service' ?>
    </button>
  </form>
</div>

<?php
renderAdminFooter();
?>
