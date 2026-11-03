<?php
require_once __DIR__ . '/../includes/functions.php';
require_login(); // staff only
$title = 'Add donor';
$donor = []; $errors = [];

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$donor, $errors] = validate_donor($_POST);
    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO donors (full_name, blood_type, phone, email, city, last_donation_date, available)
                               VALUES (:full_name, :blood_type, :phone, :email, :city, :last_donation_date, :available)');
        $stmt->execute($donor);
        flash('Donor added successfully.');
        redirect('index.php');
    }
}
require __DIR__ . '/../includes/header.php';
echo '<h1>Add donor</h1>';
require __DIR__ . '/../includes/form.php';
require __DIR__ . '/../includes/footer.php';
