<?php
// ============================================================
// Eagle Achievers — Admin Header Include
// ============================================================
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_admin();

$admin = current_admin();
$admin_page_title = $admin_page_title ?? 'Admin Panel';

// Pending counts for badges
$pending_payouts = $pdo->query("SELECT COUNT(*) FROM payout_requests WHERE status='pending'")->fetchColumn();
$pending_messages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();

$current_page = basename($_SERVER['PHP_SELF']);
function is_active(string ...$pages): string {
    global $current_page;
    return in_array($current_page, $pages) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($admin_page_title) ?> — Eagle Achievers Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Bebas+Neue&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/admin/admin.css">
</head>
<body>
<div id="admin-toast"></div>

<div class="admin-wrap">
  <!-- SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon-sm">🦅</div>
      <div>
        <div class="logo-name-sm">EAGLE ACHIEVERS</div>
        <div class="logo-sub-sm">Admin Panel</div>
      </div>
    </div>

    <div class="sidebar-section">Dashboard</div>
    <nav class="sidebar-nav">
      <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-link <?= is_active('index.php') ?>"><span class="ic">📊</span>Overview</a>
    </nav>

    <div class="sidebar-section">Management</div>
    <nav class="sidebar-nav">
      <a href="<?= SITE_URL ?>/admin/pages/users.php" class="sidebar-link <?= is_active('users.php') ?>"><span class="ic">👥</span>Users</a>
      <a href="<?= SITE_URL ?>/admin/pages/affiliates.php" class="sidebar-link <?= is_active('affiliates.php') ?>"><span class="ic">🔗</span>Affiliates</a>
      <a href="<?= SITE_URL ?>/admin/pages/courses.php" class="sidebar-link <?= is_active('courses.php') ?>"><span class="ic">📚</span>Courses</a>
      <a href="<?= SITE_URL ?>/admin/pages/orders.php" class="sidebar-link <?= is_active('orders.php') ?>"><span class="ic">🛒</span>Orders</a>
      <a href="<?= SITE_URL ?>/admin/pages/enrollments.php" class="sidebar-link <?= is_active('enrollments.php') ?>"><span class="ic">🎓</span>Enrollments</a>
    </nav>

    <div class="sidebar-section">Finance</div>
    <nav class="sidebar-nav">
      <a href="<?= SITE_URL ?>/admin/pages/commissions.php" class="sidebar-link <?= is_active('commissions.php') ?>"><span class="ic">💰</span>Commissions</a>
      <a href="<?= SITE_URL ?>/admin/pages/payouts.php" class="sidebar-link <?= is_active('payouts.php') ?>">
        <span class="ic">💳</span>Payouts
        <?php if ($pending_payouts > 0): ?><span class="sidebar-badge"><?= $pending_payouts ?></span><?php endif; ?>
      </a>
    </nav>

    <div class="sidebar-section">Content</div>
    <nav class="sidebar-nav">
      <a href="<?= SITE_URL ?>/admin/pages/testimonials.php" class="sidebar-link <?= is_active('testimonials.php') ?>"><span class="ic">⭐</span>Testimonials</a>
      <a href="<?= SITE_URL ?>/admin/pages/messages.php" class="sidebar-link <?= is_active('messages.php') ?>">
        <span class="ic">✉️</span>Messages
        <?php if ($pending_messages > 0): ?><span class="sidebar-badge"><?= $pending_messages ?></span><?php endif; ?>
      </a>
      <a href="<?= SITE_URL ?>/admin/pages/settings.php" class="sidebar-link <?= is_active('settings.php') ?>"><span class="ic">⚙️</span>Settings</a>
    </nav>

    <div class="sidebar-footer">
      <div style="font-size:13px;color:var(--white);font-weight:600;margin-bottom:4px;"><?= htmlspecialchars($admin['name']) ?></div>
      <div style="font-size:11px;color:var(--grey-mid);margin-bottom:12px;"><?= htmlspecialchars($admin['email']) ?></div>
      <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-link" style="color:#f87171;"><span class="ic">🚪</span>Logout</a>
    </div>
  </aside>

  <!-- MAIN -->
  <div style="display:flex;flex-direction:column;min-height:100vh;overflow:hidden;">
    <!-- TOPBAR -->
    <header class="admin-topbar">
      <div class="topbar-title"><?= htmlspecialchars($admin_page_title) ?></div>
      <div class="topbar-right">
        <span style="font-size:12px;color:var(--grey-mid);"><?= date('d M Y') ?></span>
        <a href="<?= SITE_URL ?>" target="_blank" style="font-size:12px;color:var(--gold);">↗ View Site</a>
        <div class="admin-avatar"><?= strtoupper(substr($admin['name'], 0, 1)) ?></div>
      </div>
    </header>

    <!-- CONTENT -->
    <main class="admin-content">
