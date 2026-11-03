<?php
require_once __DIR__ . '/../includes/functions.php';
require_login(); // staff only
$title = 'Edit donor';
$id = (int)($_GET['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM donors WHERE id = ?');
$stmt->execute([$id]);
$donor = $stmt->fetch();
if (!$donor) { flash('Donor not found.', 'error'); redirect('index.php'); }
$errors = [];

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$data, $errors] = validate_donor($_POST);
    $donor = array_merge($donor, $data);
    if (!$errors) {
        $data['id'] = $id;
        $up = db()->prepare('UPDATE donors SET full_name=:full_name, blood_type=:blood_type, phone=:phone, email=:email,
                             city=:city, last_donation_date=:last_donation_date, available=:available WHERE id=:id');
        $up->execute($data);
        flash('Donor updated successfully.');
        redirect('index.php');
    }
}
require __DIR__ . '/../includes/header.php';
echo '<h1>Edit donor</h1>';
require __DIR__ . '/../includes/form.php';
require __DIR__ . '/../includes/footer.php';
