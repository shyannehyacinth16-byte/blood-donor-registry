<?php
require_once __DIR__ . '/../includes/functions.php';
require_login(); // staff only

// DELETE (POST only, CSRF-protected)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
csrf_check();
$stmt = db()->prepare('DELETE FROM donors WHERE id = ?');
$stmt->execute([(int)($_POST['id'] ?? 0)]);
flash('Donor deleted.');
redirect('index.php');
