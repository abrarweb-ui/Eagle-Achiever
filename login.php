<?php
$page_title = 'Login';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) redirect(SITE_URL . '/user/dashboard.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $email = trim(strtolower($_POST['email'] ?? ''));
        $pass  = $_POST['password'] ?? '';

        if (empty($email) || empty($pass)) {
            $errors[] = 'Please enter your email and password.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && $user['status'] === 'banned') {
                $errors[] = 'Your account has been suspended. Please contact support.';
            } elseif ($user && password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $pdo->prepare("UPDATE users SET last_login=NOW() WHERE id=?")->execute([$user['id']]);
                log_activity('User login', "Email: $email", $user['id']);
                $redir = $_SESSION['redirect_after_login'] ?? SITE_URL . '/user/dashboard.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redir);
            } else {
                $errors[] = 'Invalid email or password. Please try again.';
            }
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<section style="min-height:100vh;display:flex;align-items:center;padding:100px 20px 60px;background:radial-gradient(ellipse 60% 60% at 50% 30%,rgba(201,168,76,.07) 0%,transparent 70%),var(--navy);">
  <div class="container" style="max-width:440px;">
    <div class="card card-body" style="padding:40px;">
      <div style="text-align:center;margin-bottom:36px;">
        <a href="<?= SITE_URL ?>" class="logo" style="justify-content:center;margin-bottom:20px;display:flex;">
          <div class="logo-icon">🦅</div>
          <div><div class="logo-name">EAGLE ACHIEVERS</div></div>
        </a>
        <h2 style="font-size:26px;margin-bottom:8px;">Welcome Back</h2>
        <p style="font-size:14px;color:var(--grey-mid);">Login to access your courses and dashboard</p>
      </div>

      <?php if ($errors): ?>
        <div class="alert alert-error"><?= implode('<br>', $errors) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['registered'])): ?>
        <div class="alert alert-success">✅ Account created! Please login.</div>
      <?php endif; ?>

      <form method="POST">
        <?= csrf_field() ?>
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Your password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:16px;margin-bottom:16px;">Login →</button>
        <p style="text-align:center;font-size:13px;color:var(--grey-mid);">
          Don't have an account? <a href="<?= SITE_URL ?>/register.php" style="color:var(--gold);">Register free</a>
        </p>
      </form>
    </div>
    <p style="text-align:center;font-size:12px;color:var(--grey-mid);margin-top:20px;">
      Admin? <a href="<?= SITE_URL ?>/admin/login.php" style="color:var(--grey-mid);">Admin login →</a>
    </p>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
