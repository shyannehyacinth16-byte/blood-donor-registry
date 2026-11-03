<?php
require_once __DIR__ . '/../includes/functions.php';
$title = 'Donors · Blood Donor Registry';

// READ (with search + filter)
$q    = trim($_GET['q'] ?? '');
$type = $_GET['type'] ?? '';
$sql  = 'SELECT * FROM donors WHERE 1=1';
$args = [];
if ($q !== '')  { $sql .= ' AND (full_name LIKE ? OR city LIKE ?)'; $args[] = "%$q%"; $args[] = "%$q%"; }
if (in_array($type, BLOOD_TYPES, true)) { $sql .= ' AND blood_type = ?'; $args[] = $type; }
$sql .= ' ORDER BY available DESC, created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($args);
$donors = $stmt->fetchAll();

$stats = db()->query('SELECT COUNT(*) total, COALESCE(SUM(available),0) available FROM donors')->fetch();

require __DIR__ . '/../includes/header.php';
?>
<section class="stats">
  <div class="card"><strong><?= (int)$stats['total'] ?></strong><span>Registered donors</span></div>
  <div class="card"><strong><?= (int)$stats['available'] ?></strong><span>Available now</span></div>
</section>

<form class="filters" method="get">
  <input name="q" placeholder="Search name or city…" value="<?= e($q) ?>">
  <select name="type">
    <option value="">All blood types</option>
    <?php foreach (BLOOD_TYPES as $t): ?>
      <option <?= $type === $t ? 'selected' : '' ?>><?= e($t) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn" type="submit">Filter</button>
</form>

<div class="table-wrap">
<table>
  <thead><tr><th>Name</th><th>Type</th><th>Phone</th><th>City</th><th>Last donation</th><th>Status</th><?php if (is_logged_in()): ?><th></th><?php endif; ?></tr></thead>
  <tbody>
  <?php if (!$donors): ?>
    <tr><td colspan="<?= is_logged_in() ? 7 : 6 ?>" class="empty">No donors found.</td></tr>
  <?php endif; ?>
  <?php foreach ($donors as $d): ?>
    <tr>
      <td><?= e($d['full_name']) ?><br><small><?= e($d['email']) ?></small></td>
      <td><span class="badge"><?= e($d['blood_type']) ?></span></td>
      <td><?= e($d['phone']) ?></td>
      <td><?= e($d['city']) ?></td>
      <td><?= e($d['last_donation_date'] ?? '—') ?></td>
      <td><?= $d['available'] ? '<span class="ok">Available</span>' : '<span class="no">Unavailable</span>' ?></td>
      <?php if (is_logged_in()): ?>
      <td class="row-actions">
        <a class="btn small ghost" href="edit.php?id=<?= (int)$d['id'] ?>">Edit</a>
        <form method="post" action="delete.php" onsubmit="return confirm('Delete this donor?');">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
          <button class="btn small danger" type="submit">Delete</button>
        </form>
      </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
