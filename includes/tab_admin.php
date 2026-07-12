<?php
$all = get_all_recruitments();
?>

<section class="page-header">
  <p class="eyebrow">ADMIN PANEL</p>
  <h1>Manage Recruitment</h1>
  <p class="page-sub">Only accessible to the CEO, COO, CDO, CPA, or OM role.</p>
</section>

<div class="admin-form-card">
  <h2>Open New Recruitment</h2>
  <form method="POST" action="dashboard.php?tab=admin">
    <input type="hidden" name="action" value="open_recruitment">

    <label for="position">Position</label>
    <input type="text" id="position" name="position" placeholder="e.g. First Officer, Flight Attendant, ATC Staff" required>

    <label for="description">Description (optional)</label>
    <textarea id="description" name="description" rows="4" placeholder="Requirements, duties, or extra info..."></textarea>

    <label for="apply_link">Apply Link</label>
    <input type="url" id="apply_link" name="apply_link" placeholder="https://forms.gle/..." required>

    <label for="open_until">Open Until</label>
    <input type="date" id="open_until" name="open_until" required>

    <button type="submit" class="btn-primary">Open Recruitment</button>
  </form>
</div>

<div class="admin-list-card">
  <h2>Recruitment History</h2>
  <?php if (empty($all)): ?>
    <p class="muted">No recruitment posts have been created yet.</p>
  <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Position</th>
          <th>Open Until</th>
          <th>Status</th>
          <th>Created By</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($all as $r):
          $isExpired = strtotime($r['open_until']) < strtotime('today');
          $status = ($r['is_open'] && !$isExpired) ? 'Open' : 'Closed';
        ?>
          <tr>
            <td><?= e($r['position']) ?></td>
            <td><?= e(date('d M Y', strtotime($r['open_until']))) ?></td>
            <td><span class="status-pill status-<?= $status === 'Open' ? 'open' : 'closed' ?>"><?= $status ?></span></td>
            <td><?= e($r['created_by']) ?></td>
            <td>
              <?php if ($status === 'Open'): ?>
                <form method="POST" action="dashboard.php?tab=admin" onsubmit="return confirm('Close this recruitment post?');">
                  <input type="hidden" name="action" value="close_recruitment">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button type="submit" class="btn-small-danger">Close</button>
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
