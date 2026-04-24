<?php
$admin_page_title = 'Dashboard Overview';
require_once __DIR__ . '/../includes/header.php';

// Stats
$total_users      = $pdo->query("SELECT COUNT(*) FROM users WHERE status='active'")->fetchColumn();
$total_affiliates = $pdo->query("SELECT COUNT(*) FROM users WHERE is_affiliate=1 AND affiliate_approved=1")->fetchColumn();
$total_orders     = $pdo->query("SELECT COUNT(*) FROM orders WHERE payment_status='paid'")->fetchColumn();
$total_revenue    = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM orders WHERE payment_status='paid'")->fetchColumn();
$total_commissions= $pdo->query("SELECT COALESCE(SUM(commission_amount),0) FROM affiliate_commissions")->fetchColumn();
$pending_payouts  = $pdo->query("SELECT COUNT(*) FROM payout_requests WHERE status='pending'")->fetchColumn();
$pending_messages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
$active_courses   = $pdo->query("SELECT COUNT(*) FROM courses WHERE status='active'")->fetchColumn();

// Recent orders
$recent_orders = $pdo->query("SELECT o.*, u.name AS user_name, c.title AS course_title FROM orders o JOIN users u ON u.id=o.user_id JOIN courses c ON c.id=o.course_id ORDER BY o.created_at DESC LIMIT 8")->fetchAll();

// Recent signups
$recent_users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 6")->fetchAll();

// Revenue last 7 days (chart data)
$chart = $pdo->query("SELECT DATE(created_at) AS day, SUM(amount) AS total FROM orders WHERE payment_status='paid' AND created_at >= DATE_SUB(NOW(),INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY day ASC")->fetchAll();
$chart_labels = json_encode(array_column($chart, 'day'));
$chart_data   = json_encode(array_map(fn($r) => floatval($r['total']), $chart));
?>

<!-- STATS -->
<div class="stats-grid">
  <div class="stat-card-admin"><div class="stat-icon-admin">👥</div><div class="stat-label-admin">Total Users</div><div class="stat-num-admin"><?= number_format($total_users) ?></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">🔗</div><div class="stat-label-admin">Active Affiliates</div><div class="stat-num-admin"><?= number_format($total_affiliates) ?></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">🛒</div><div class="stat-label-admin">Total Orders</div><div class="stat-num-admin"><?= number_format($total_orders) ?></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">💰</div><div class="stat-label-admin">Total Revenue</div><div class="stat-num-admin">₹<span><?= number_format($total_revenue, 0) ?></span></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">🤝</div><div class="stat-label-admin">Commissions Paid</div><div class="stat-num-admin">₹<span><?= number_format($total_commissions, 0) ?></span></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">⏳</div><div class="stat-label-admin">Pending Payouts</div><div class="stat-num-admin" style="color:var(--gold);"><?= number_format($pending_payouts) ?></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">📚</div><div class="stat-label-admin">Active Courses</div><div class="stat-num-admin"><?= number_format($active_courses) ?></div></div>
  <div class="stat-card-admin"><div class="stat-icon-admin">✉️</div><div class="stat-label-admin">New Messages</div><div class="stat-num-admin" style="color:var(--blue-bright);"><?= number_format($pending_messages) ?></div></div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
  <!-- REVENUE CHART -->
  <div class="admin-card">
    <div class="admin-card-header"><h4>📈 Revenue (Last 7 Days)</h4></div>
    <div class="admin-card-body">
      <canvas id="revenueChart" height="120"></canvas>
    </div>
  </div>
  <!-- QUICK ACTIONS -->
  <div class="admin-card">
    <div class="admin-card-header"><h4>⚡ Quick Actions</h4></div>
    <div class="admin-card-body" style="display:flex;flex-direction:column;gap:10px;">
      <a href="<?= SITE_URL ?>/admin/pages/courses.php?action=add" class="btn-a btn-gold" style="justify-content:center;">+ Add New Course</a>
      <a href="<?= SITE_URL ?>/admin/pages/payouts.php" class="btn-a btn-outline-a" style="justify-content:center;">💳 Review Payouts <?php if($pending_payouts): ?><span style="background:var(--gold);color:var(--navy);font-size:10px;padding:1px 6px;border-radius:100px;margin-left:4px;"><?= $pending_payouts ?></span><?php endif; ?></a>
      <a href="<?= SITE_URL ?>/admin/pages/messages.php" class="btn-a btn-outline-a" style="justify-content:center;">✉️ Messages <?php if($pending_messages): ?><span style="background:var(--blue);color:#fff;font-size:10px;padding:1px 6px;border-radius:100px;margin-left:4px;"><?= $pending_messages ?></span><?php endif; ?></a>
      <a href="<?= SITE_URL ?>/admin/pages/users.php" class="btn-a btn-outline-a" style="justify-content:center;">👥 Manage Users</a>
      <a href="<?= SITE_URL ?>/admin/pages/settings.php" class="btn-a btn-outline-a" style="justify-content:center;">⚙️ Settings</a>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  <!-- RECENT ORDERS -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h4>🛒 Recent Orders</h4>
      <a href="<?= SITE_URL ?>/admin/pages/orders.php" style="font-size:12px;color:var(--gold);">View All →</a>
    </div>
    <div class="tbl-wrapper" style="border:none;border-radius:0;">
      <table class="admin-table">
        <thead><tr><th>User</th><th>Course</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach ($recent_orders as $o): ?>
          <tr>
            <td><?= htmlspecialchars($o['user_name']) ?></td>
            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($o['course_title']) ?></td>
            <td style="color:var(--gold);font-weight:600;">₹<?= number_format($o['amount'], 0) ?></td>
            <td><span class="badge-a badge-<?= $o['payment_status']==='paid'?'green':'red' ?>-a"><?= ucfirst($o['payment_status']) ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($recent_orders)): ?><tr><td colspan="4" style="text-align:center;padding:20px;color:var(--grey-mid);">No orders yet</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- RECENT USERS -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h4>👥 Recent Signups</h4>
      <a href="<?= SITE_URL ?>/admin/pages/users.php" style="font-size:12px;color:var(--gold);">View All →</a>
    </div>
    <div class="tbl-wrapper" style="border:none;border-radius:0;">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Affiliate</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($recent_users as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td style="font-size:12px;color:var(--grey-mid);"><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['affiliate_approved'] ? '<span class="badge-a badge-green-a">Yes</span>' : '<span class="badge-a badge-grey-a">No</span>' ?></td>
            <td style="font-size:12px;color:var(--grey-mid);"><?= date('d M', strtotime($u['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart');
if(ctx){
  new Chart(ctx,{type:'line',data:{labels:<?= $chart_labels ?>,datasets:[{label:'Revenue (₹)',data:<?= $chart_data ?>,borderColor:'#c9a84c',backgroundColor:'rgba(201,168,76,.1)',borderWidth:2,tension:.4,fill:true,pointBackgroundColor:'#c9a84c',pointRadius:4}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{grid:{color:'rgba(255,255,255,.05)'},ticks:{color:'#8892a4',callback:v=>'₹'+v}},x:{grid:{color:'rgba(255,255,255,.05)'},ticks:{color:'#8892a4'}}}}});
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
