<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_admin()) redirect(SITE_URL . '/admin/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';
    $stmt  = $pdo->prepare("SELECT * FROM admins WHERE email=?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($pass, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $pdo->prepare("UPDATE admins SET last_login=NOW() WHERE id=?")->execute([$admin['id']]);
        log_activity('Admin login', '', $admin['id'], 'admin');
        redirect(SITE_URL . '/admin/index.php');
    } else {
        $error = 'Invalid credentials. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Admin Login — Eagle Achievers</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/admin/admin.css">
<style>
body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:radial-gradient(ellipse 60% 60% at 50% 30%,rgba(26,120,240,.1) 0%,transparent 70%),var(--navy);}
.login-box{width:100%;max-width:400px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:40px;margin:20px;}
</style>
</head>
<body>
<div class="login-box">
  <div style="text-align:center;margin-bottom:32px;">
    <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--gold),#a87830);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 14px;box-shadow:0 4px 20px rgba(201,168,76,.3);">🦅</div>
    <div style="font-family:'Bebas Neue';font-size:22px;letter-spacing:.1em;color:var(--white);">EAGLE ACHIEVERS</div>
    <div style="font-size:13px;color:var(--grey-mid);margin-top:4px;">Admin Panel Access</div>
  </div>
  <?php if ($error): ?>
  <div class="alert-a alert-error-a"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="form-group-a">
      <label class="form-label-a">Email Address</label>
      <input type="email" name="email" class="form-ctrl" placeholder="admin@eagleachievers.in" required autofocus>
    </div>
    <div class="form-group-a">
      <label class="form-label-a">Password</label>
      <input type="password" name="password" class="form-ctrl" placeholder="Your password" required>
    </div>
    <button type="submit" class="btn-a btn-gold" style="width:100%;justify-content:center;padding:14px;margin-top:4px;">Login to Admin Panel →</button>
  </form>
  <p style="text-align:center;font-size:12px;color:var(--grey-mid);margin-top:20px;">
    Default: admin@eagleachievers.in / password
  </p>
</div>
</body>
</html>
