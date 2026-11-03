<?php require_once __DIR__ . '/functions.php'; $flash = flash(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Blood Donor Registry') ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="top">
  <div class="wrap">
    <a class="brand" href="index.php">🩸 Blood Donor Registry</a>
    <nav class="nav">
      <?php if (is_logged_in()): ?>
        <span class="who">Signed in as <strong><?= e($_SESSION['username']) ?></strong></span>
        <a class="btn" href="create.php">+ Add donor</a>
        <form method="post" action="logout.php">
          <?= csrf_field() ?>
          <button class="btn ghost" type="submit">Log out</button>
        </form>
      <?php else: ?>
        <a class="btn" href="login.php">Staff login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="wrap">
<?php if ($flash): ?>
  <div class="flash <?= e($flash[1]) ?>"><?= e($flash[0]) ?></div>
<?php endif; ?>
