<?php
$page_title = 'Register';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) redirect(SITE_URL . '/user/dashboard.php');

$errors = [];
$success = '';
$ref_code = get_ref_code();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $name  = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $pass  = $_POST['password'] ?? '';
        $pass2 = $_POST['password2'] ?? '';
        $ref   = trim($_POST['ref_code'] ?? '');

        if (strlen($name) < 2)              $errors[] = 'Name must be at least 2 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
        if (strlen($pass) < 8)              $errors[] = 'Password must be at least 8 characters.';
        if ($pass !== $pass2)               $errors[] = 'Passwords do not match.';

        if (empty($errors)) {
            // Check email unique
            $chk = $pdo->prepare("SELECT id FROM users WHERE email=?");
            $chk->execute([$email]);
            if ($chk->fetch()) {
                $errors[] = 'This email is already registered. <a href="login.php">Login instead →</a>';
            } else {
                // Find referrer
                $referrer_id = null;
                if ($ref) {
                    $r = $pdo->prepare("SELECT id FROM users WHERE referral_code=?");
                    $r->execute([$ref]);
                    $referrer = $r->fetch();
                    if ($referrer) $referrer_id = $referrer['id'];
                }
                $referral_code = generate_referral_code($name);
                $hashed = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,password,referral_code,referred_by,email_verified) VALUES (?,?,?,?,?,?,1)");
                $stmt->execute([$name, $email, $phone, $hashed, $referral_code, $referrer_id]);
                $new_user_id = $pdo->lastInsertId();
                // Track referral signup
                if ($referrer_id) {
                    $ins = $pdo->prepare("INSERT INTO referrals (referral_code,affiliate_user_id,referred_user_id) VALUES (?,?,?)");
                    $ins->execute([$ref, $referrer_id, $new_user_id]);
                    add_notification($referrer_id, 'New Referral!', "$name just signed up using your referral link.", 'success', '/user/dashboard.php');
                }
                // Login user
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_name'] = $name;
                log_activity('User registered', "Email: $email", $new_user_id);
                // Clear ref cookie
                setcookie('ea_ref', '', time() - 3600, '/');
                $redir = $_SESSION['redirect_after_login'] ?? SITE_URL . '/user/dashboard.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redir . '?welcome=1');
            }
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<section style="min-height:100vh;display:flex;align-items:center;padding:100px 20px 60px;background:radial-gradient(ellipse 60% 60% at 50% 30%,rgba(26,120,240,.08) 0%,transparent 70%),var(--navy);">
  <div class="container" style="max-width:480px;">
    <div class="card card-body" style="padding:40px;">
      <div style="text-align:center;margin-bottom:36px;">
        <a href="<?= SITE_URL ?>" class="logo" style="justify-content:center;margin-bottom:20px;display:flex;">
          <div class="logo-icon">🦅</div>
          <div><div class="logo-name">EAGLE ACHIEVERS</div></div>
        </a>
        <h2 style="font-size:26px;margin-bottom:8px;">Create Your Account</h2>
        <p style="font-size:14px;color:var(--grey-mid);">Join 5,000+ achievers transforming their lives</p>
      </div>

      <?php if ($errors): ?>
        <div class="alert alert-error"><?= implode('<br>', $errors) ?></div>
      <?php endif; ?>

      <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="ref_code" value="<?= htmlspecialchars($ref_code) ?>">
        <div class="form-group">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" placeholder="Your full name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number</label>
          <input type="tel" name="phone" class="form-control" placeholder="+91 9876543210" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password2" class="form-control" placeholder="Repeat password" required>
        </div>
        <?php if ($ref_code): ?>
        <div style="padding:12px 16px;background:rgba(201,168,76,.08);border:1px solid var(--border);border-radius:8px;margin-bottom:20px;font-size:13px;color:var(--gold);">
          ✦ You were referred by a member. They'll earn a commission when you buy a course!
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:16px;">Create Account →</button>
        <p style="text-align:center;font-size:13px;color:var(--grey-mid);margin-top:20px;">
          Already have an account? <a href="<?= SITE_URL ?>/login.php" style="color:var(--gold);">Login here</a>
        </p>
        <p style="text-align:center;font-size:11px;color:var(--grey-mid);margin-top:12px;">
          By registering you agree to our <a href="<?= SITE_URL ?>/pages/terms.php" style="color:var(--grey-mid);text-decoration:underline;">Terms</a> and <a href="<?= SITE_URL ?>/pages/privacy.php" style="color:var(--grey-mid);text-decoration:underline;">Privacy Policy</a>
        </p>
      </form>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
