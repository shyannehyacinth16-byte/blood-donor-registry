<?php /** @var array $donor @var array $errors */ ?>
<?php if (!empty($errors)): ?>
  <div class="flash error"><ul><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<form method="post" class="card form">
  <?= csrf_field() ?>
  <label>Full name *<input name="full_name" maxlength="100" required value="<?= e($donor['full_name'] ?? '') ?>"></label>
  <label>Blood type *
    <select name="blood_type" required>
      <option value="">Select…</option>
      <?php foreach (BLOOD_TYPES as $t): ?>
        <option <?= ($donor['blood_type'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Phone *<input name="phone" required value="<?= e($donor['phone'] ?? '') ?>"></label>
  <label>Email<input type="email" name="email" value="<?= e($donor['email'] ?? '') ?>"></label>
  <label>City *<input name="city" required value="<?= e($donor['city'] ?? '') ?>"></label>
  <label>Last donation date<input type="date" name="last_donation_date" max="<?= date('Y-m-d') ?>" value="<?= e($donor['last_donation_date'] ?? '') ?>"></label>
  <label class="check"><input type="checkbox" name="available" <?= !isset($donor['available']) || $donor['available'] ? 'checked' : '' ?>> Currently available to donate</label>
  <div class="actions">
    <button class="btn" type="submit">Save</button>
    <a class="btn ghost" href="index.php">Cancel</a>
  </div>
</form>
