<?php
$all = get_all_recruitments();
?>

<section class="page-header">
  <p class="eyebrow">ADMIN PANEL</p>
  <h1>Manage Recruitment</h1>
  <p class="page-sub">Hanya bisa diakses oleh role CEO, COO, CDO, CPA, atau OM.</p>
</section>

<div class="admin-form-card">
  <h2>Buka Recruitment Baru</h2>
  <form method="POST" action="dashboard.php?tab=admin">
    <input type="hidden" name="action" value="open_recruitment">

    <label for="position">Jabatan</label>
    <input type="text" id="position" name="position" placeholder="Contoh: First Officer, Flight Attendant, ATC Staff" required>

    <label for="description">Penjelasan (opsional)</label>
    <textarea id="description" name="description" rows="4" placeholder="Persyaratan, tugas, atau info tambahan..."></textarea>

    <label for="apply_link">Link Apply</label>
    <input type="url" id="apply_link" name="apply_link" placeholder="https://forms.gle/..." required>

    <label for="open_until">Dibuka Sampai Tanggal</label>
    <input type="date" id="open_until" name="open_until" required>

    <button type="submit" class="btn-primary">Buka Recruitment</button>
  </form>
</div>

<div class="admin-list-card">
  <h2>Riwayat Recruitment</h2>
  <?php if (empty($all)): ?>
    <p class="muted">Belum ada recruitment yang pernah dibuat.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Jabatan</th>
          <th>Dibuka Sampai</th>
          <th>Status</th>
          <th>Dibuat Oleh</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all as $r):
          $isExpired = strtotime($r['open_until']) < strtotime('today');
          $status = ($r['is_open'] && !$isExpired) ? 'Terbuka' : 'Tertutup';
        ?>
          <tr>
            <td><?= e($r['position']) ?></td>
            <td><?= e(date('d M Y', strtotime($r['open_until']))) ?></td>
            <td><span class="status-pill status-<?= $status === 'Terbuka' ? 'open' : 'closed' ?>"><?= $status ?></span></td>
            <td><?= e($r['created_by']) ?></td>
            <td>
              <?php if ($status === 'Terbuka'): ?>
                <form method="POST" action="dashboard.php?tab=admin" onsubmit="return confirm('Tutup recruitment ini?');">
                  <input type="hidden" name="action" value="close_recruitment">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button type="submit" class="btn-small-danger">Tutup</button>
                </form>
              <?php else: ?>
                &mdash;
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
