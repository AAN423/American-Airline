<?php
$active = get_active_recruitments();
?>

<section class="page-header">
  <p class="eyebrow">CAREERS</p>
  <h1>Recruitment</h1>
</section>

<?php if (empty($active)): ?>
  <div class="empty-state">
    <img src="assets/img/logo.png" alt="" class="empty-logo">
    <h2>Recruitment is currently closed</h2>
    <p>There are no open positions right now. Check back later or watch our Discord for announcements.</p>
  </div>
<?php else: ?>
  <div class="recruit-grid">
    <?php foreach ($active as $r): ?>
      <div class="recruit-card">
        <h3><?= e($r['position']) ?></h3>
        <?php if (!empty($r['description'])): ?>
          <p class="recruit-desc"><?= nl2br(e($r['description'])) ?></p>
        <?php endif; ?>
        <p class="recruit-until">Open until <strong><?= e(date('d M Y', strtotime($r['open_until']))) ?></strong></p>
        <a href="<?= e($r['apply_link']) ?>" target="_blank" rel="noopener" class="btn-apply">Apply Now</a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
