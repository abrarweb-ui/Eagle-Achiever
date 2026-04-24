<?php
$page_title = 'Home';
$page_desc = 'Eagle Achievers — The Last System For Your Best Life. Premium skill-based digital courses to help you earn, grow, and succeed.';
require_once __DIR__ . '/includes/header.php';
$featured_courses = get_courses(['status' => 'active', 'featured' => true, 'limit' => 6]);
$testimonials = (function() use ($pdo) {
    $s = $pdo->query("SELECT * FROM testimonials WHERE status=1 AND is_featured=1 ORDER BY sort_order ASC LIMIT 6");
    return $s->fetchAll();
})();
?>

<!-- HERO -->
<section style="position:relative;min-height:100vh;display:flex;align-items:center;overflow:hidden;padding-top:76px;">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 60% 40%,rgba(26,120,240,.12) 0%,transparent 70%),radial-gradient(ellipse 50% 40% at 20% 80%,rgba(201,168,76,.1) 0%,transparent 60%),linear-gradient(180deg,var(--navy) 0%,var(--navy-mid) 100%);"></div>
  <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.02) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black 0%,transparent 100%);"></div>
  <div class="container" style="position:relative;z-index:2;padding-top:clamp(60px,10vw,120px);padding-bottom:clamp(60px,8vw,100px);">
    <div style="max-width:720px;">
      <div class="fade-up" style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
        <div style="width:36px;height:1px;background:var(--gold);"></div>
        <span style="font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);">Premium Digital Education Platform</span>
      </div>
      <h1 class="fade-up d1" style="margin-bottom:24px;">
        Stop Surviving.<br>Start <em style="font-style:italic;color:var(--gold);">Earning</em><br>What You're Worth.
      </h1>
      <p class="fade-up d2 section-sub" style="margin-bottom:44px;max-width:540px;">
        Eagle Achievers is the last education system you'll ever need — built for ambitious minds ready to acquire high-income skills, build digital income, and transform their lives permanently.
      </p>
      <div class="fade-up d3" style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:52px;">
        <a href="<?= SITE_URL ?>/pages/courses.php" class="btn btn-primary"><span>Explore Courses</span><span>→</span></a>
        <a href="<?= SITE_URL ?>/pages/affiliate.php" class="btn btn-secondary">Become an Affiliate</a>
      </div>
      <div class="fade-up d4" style="display:flex;gap:32px;padding-top:28px;border-top:1px solid rgba(255,255,255,.08);flex-wrap:wrap;">
        <div><div style="font-family:var(--ff-heading);font-size:34px;letter-spacing:.04em;color:var(--white);">5000<span style="color:var(--gold);">+</span></div><div style="font-size:11px;color:var(--grey-mid);text-transform:uppercase;letter-spacing:.1em;margin-top:3px;">Students</div></div>
        <div><div style="font-family:var(--ff-heading);font-size:34px;color:var(--white);">₹2<span style="color:var(--gold);">Cr+</span></div><div style="font-size:11px;color:var(--grey-mid);text-transform:uppercase;letter-spacing:.1em;margin-top:3px;">Earned by Students</div></div>
        <div><div style="font-family:var(--ff-heading);font-size:34px;color:var(--white);">6<span style="color:var(--gold);">+</span></div><div style="font-size:11px;color:var(--grey-mid);text-transform:uppercase;letter-spacing:.1em;margin-top:3px;">Premium Courses</div></div>
        <div><div style="font-family:var(--ff-heading);font-size:34px;color:var(--white);">98<span style="color:var(--gold);">%</span></div><div style="font-size:11px;color:var(--grey-mid);text-transform:uppercase;letter-spacing:.1em;margin-top:3px;">Satisfaction</div></div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED COURSES -->
<section class="section" style="background:var(--navy-mid);position:relative;">
  <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,.3),transparent);"></div>
  <div class="container">
    <div class="section-header fade-up">
      <div class="tag">Our Programmes</div>
      <h2>Skills That Pay.<br>Courses That Deliver.</h2>
      <p class="section-sub" style="margin-top:14px;">Each course is built around one goal: getting you to a level where you can earn consistently from your skills.</p>
    </div>
    <div class="grid-3">
      <?php foreach ($featured_courses as $i => $c): ?>
      <div class="course-card fade-up d<?= ($i%4)+1 ?> <?= $c['is_featured'] ? 'featured-course' : '' ?>">
        <?php if ($c['is_featured']): ?><div class="course-badge badge badge-gold" style="position:absolute;top:16px;right:16px;z-index:2;">⭐ Featured</div><?php endif; ?>
        <div class="course-thumb" style="background:linear-gradient(135deg,rgba(201,168,76,.1),rgba(26,120,240,.08));">
          <div class="course-thumb-icon"><?= htmlspecialchars($c['category_icon'] ?? '📚') ?></div>
        </div>
        <div class="course-body">
          <div class="course-cat"><?= htmlspecialchars($c['category_name'] ?? '') ?></div>
          <h3 class="course-title"><?= htmlspecialchars($c['title']) ?></h3>
          <p class="course-desc"><?= htmlspecialchars($c['short_description']) ?></p>
          <?php
          $benefits = is_array($c['benefits']) ? $c['benefits'] : json_decode($c['benefits'] ?? '[]', true);
          if ($benefits): ?>
          <div class="course-benefits">
            <?php foreach (array_slice($benefits, 0, 3) as $b): ?>
            <div class="course-benefit"><div class="benefit-check">✓</div><?= htmlspecialchars($b) ?></div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <div class="course-footer mt-auto">
            <div class="course-price">
              <?php if ($c['price'] > 0 && $c['discounted_price']): ?>
              <div class="course-price-original">₹<?= number_format($c['price'], 0) ?></div>
              <?php endif; ?>
              <div class="course-price-current">₹<?= number_format($c['discounted_price'] ?? $c['price'], 0) ?></div>
            </div>
            <a href="<?= SITE_URL ?>/pages/course.php?slug=<?= urlencode($c['slug']) ?>" class="btn btn-primary btn-sm">View Course</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:44px;" class="fade-up">
      <a href="<?= SITE_URL ?>/pages/courses.php" class="btn btn-secondary">View All Courses →</a>
    </div>
  </div>
  <div style="position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,.3),transparent);"></div>
</section>

<!-- WHY EAGLE ACHIEVERS -->
<section class="section">
  <div class="container">
    <div class="section-header fade-up">
      <div class="tag">The Eagle System</div>
      <h2>One System. Complete Transformation.</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <?php
      $pillars = [
        ['📚','Learn','Master high-income digital skills through structured, expert-led courses built for real-world application.'],
        ['⚡','Apply','Put your skills to work immediately with live projects and guided implementation from day one.'],
        ['💰','Earn','Build consistent income streams, land clients, and achieve the financial freedom you deserve.'],
        ['🤝','Community','Join thousands of achievers who support, challenge, and celebrate each other every step of the way.'],
      ];
      foreach ($pillars as $i => $p): ?>
      <div class="card card-gold card-body fade-up d<?= $i+1 ?>" style="text-align:center;">
        <div style="font-size:44px;margin-bottom:16px;"><?= $p[0] ?></div>
        <h4 style="font-family:var(--ff-heading);font-size:26px;letter-spacing:.06em;margin-bottom:10px;"><?= $p[1] ?></h4>
        <p style="font-size:14px;color:var(--grey-mid);line-height:1.7;"><?= $p[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- AFFILIATE PROMO -->
<section class="section" style="background:linear-gradient(135deg,rgba(201,168,76,.06),rgba(26,120,240,.04));border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
      <div class="fade-up">
        <div class="tag">Affiliate Program</div>
        <h2>Earn While Others Learn.</h2>
        <p class="section-sub" style="margin:20px 0 32px;">Share Eagle Achievers with your network and earn up to 25% commission on every successful enrollment. Get your unique link and start earning today.</p>
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:36px;">
          <?php $perks = ['Up to 25% commission per sale','Lifetime tracking cookie (30 days)','Real-time earnings dashboard','Instant payout via UPI / Bank']; ?>
          <?php foreach ($perks as $pk): ?>
          <div style="display:flex;align-items:center;gap:10px;font-size:14px;"><span style="color:var(--gold);">✦</span><?= $pk ?></div>
          <?php endforeach; ?>
        </div>
        <a href="<?= SITE_URL ?>/pages/affiliate.php" class="btn btn-primary">Join Affiliate Program →</a>
      </div>
      <div class="fade-up d2">
        <div style="background:var(--navy-mid);border:1px solid var(--border);border-radius:20px;padding:36px;">
          <div style="font-family:var(--ff-display);font-size:22px;color:var(--white);margin-bottom:24px;">Your Earning Potential</div>
          <?php
          $examples = [['5 sales/month','₹999 course','₹1,248/month'], ['10 sales/month','₹1,299 course','₹2,858/month'], ['20 sales/month','₹999 avg','₹4,996/month']];
          foreach ($examples as $ex): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid rgba(255,255,255,.06);">
            <div><div style="font-size:14px;color:var(--white);font-weight:600;"><?= $ex[0] ?></div><div style="font-size:12px;color:var(--grey-mid);">at <?= $ex[1] ?></div></div>
            <div style="font-family:var(--ff-heading);font-size:22px;color:var(--gold);"><?= $ex[2] ?></div>
          </div>
          <?php endforeach; ?>
          <p style="font-size:12px;color:var(--grey-mid);margin-top:16px;">*Based on 25% commission rate. Actual earnings vary.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<?php if ($testimonials): ?>
<section class="section">
  <div class="container">
    <div class="section-header fade-up">
      <div class="tag">Real Results</div>
      <h2>5,000+ Lives Already Changed.</h2>
      <p class="section-sub" style="margin-top:14px;">This is what happens when ambitious people follow a proven system with real mentorship.</p>
    </div>
    <div class="grid-3">
      <?php foreach ($testimonials as $i => $t): ?>
      <div class="card card-body fade-up d<?= ($i%3)+1 ?>">
        <div style="display:flex;gap:3px;margin-bottom:14px;"><?= str_repeat('<span style="color:var(--gold)">★</span>', $t['rating']) ?></div>
        <div style="font-size:32px;color:var(--gold);opacity:.4;line-height:1;margin-bottom:10px;font-family:Georgia,serif;">"</div>
        <p style="font-size:14px;color:var(--text-body);line-height:1.8;margin-bottom:18px;font-style:italic;"><?= htmlspecialchars($t['content']) ?></p>
        <?php if ($t['result_badge']): ?>
        <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.15);padding:7px 13px;border-radius:100px;font-size:12px;color:var(--gold);font-weight:600;margin-bottom:18px;"><?= htmlspecialchars($t['result_badge']) ?></div>
        <?php endif; ?>
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:40px;height:40px;border-radius:50%;background:var(--navy-light);display:flex;align-items:center;justify-content:center;font-size:18px;border:2px solid var(--border);"><?= $t['avatar_emoji'] ?></div>
          <div><div style="font-size:14px;font-weight:600;color:var(--white);"><?= htmlspecialchars($t['name']) ?></div><div style="font-size:12px;color:var(--grey-mid);"><?= htmlspecialchars($t['role']) ?> — <?= htmlspecialchars($t['location']) ?></div></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FINAL CTA -->
<section class="section" style="position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 50% 50%,rgba(201,168,76,.1) 0%,transparent 70%);"></div>
  <div class="container" style="position:relative;z-index:2;">
    <div style="max-width:700px;margin:0 auto;text-align:center;" class="fade-up">
      <div style="display:inline-flex;align-items:center;gap:10px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);padding:8px 18px;border-radius:100px;font-size:12px;color:#f87171;font-weight:600;margin-bottom:32px;">
        <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;animation:pulse 1.5s infinite;"></span>
        LIMITED SEATS AVAILABLE — CLOSING SOON
      </div>
      <h2 style="margin-bottom:20px;">Your Best Life <em style="font-style:italic;color:var(--gold);">Starts Today.</em></h2>
      <p class="section-sub" style="margin:0 auto 40px;">Every day you wait is another day someone else is mastering skills and building the freedom that could have been yours.</p>
      <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;margin-bottom:24px;">
        <a href="<?= SITE_URL ?>/register.php" class="btn btn-primary" style="padding:16px 40px;font-size:14px;"><span>🚀 Start Earning Today</span></a>
        <a href="tel:+917006895694" class="btn btn-secondary">📞 Call Now</a>
      </div>
      <div style="font-size:13px;color:var(--grey-mid);">🛡️ Satisfaction Guaranteed · Real Skills · Real Results · Lifetime Access</div>
    </div>
  </div>
</section>

<style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
