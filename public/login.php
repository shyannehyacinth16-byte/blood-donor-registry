<?php
require_once __DIR__ . '/../includes/functions.php';
if (is_logged_in()) redirect('index.php');
$title = 'Staff login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (attempt_login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) {
        flash('Welcome back!');
        redirect('index.php');
    }
    $error = 'Invalid username or password.';
}
require __DIR__ . '/../includes/header.php';
?>
<h1>Staff login</h1>
<?php if ($error): ?><div class="flash error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="card form">
  <?= csrf_field() ?>
  <label>Username<input name="username" required autofocus autocomplete="username"></label>
  <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
  <div class="actions">
    <button class="btn" type="submit">Log in</button>
    <a class="btn ghost" href="index.php">Back</a>
  </div>
</form>
<?php require __DIR__ . '/../includes/footer.php'; ?>
