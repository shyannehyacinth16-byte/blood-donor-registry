<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https']);
session_start();
require_once __DIR__ . '/../config/db.php';

const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid CSRF token.');
    }
}

function flash(?string $msg = null, string $type = 'success') {
    if ($msg !== null) { $_SESSION['flash'] = [$msg, $type]; return; }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function redirect(string $url): void { header("Location: $url"); exit; }

// Returns [cleanData, errors]
function validate_donor(array $in): array {
    $d = [
        'full_name'          => trim($in['full_name'] ?? ''),
        'blood_type'         => $in['blood_type'] ?? '',
        'phone'              => trim($in['phone'] ?? ''),
        'email'              => trim($in['email'] ?? ''),
        'city'               => trim($in['city'] ?? ''),
        'last_donation_date' => trim($in['last_donation_date'] ?? ''),
        'available'          => isset($in['available']) ? 1 : 0,
    ];
    $err = [];
    if ($d['full_name'] === '' || mb_strlen($d['full_name']) > 100) $err[] = 'Full name is required (max 100 characters).';
    if (!in_array($d['blood_type'], BLOOD_TYPES, true)) $err[] = 'Select a valid blood type.';
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $d['phone'])) $err[] = 'Enter a valid phone number.';
    if ($d['email'] !== '' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $err[] = 'Enter a valid email address.';
    if ($d['city'] === '') $err[] = 'City is required.';
    if ($d['last_donation_date'] === '') {
        $d['last_donation_date'] = null;
    } else {
        $dt = DateTime::createFromFormat('Y-m-d', $d['last_donation_date']);
        if (!$dt || $dt->format('Y-m-d') !== $d['last_donation_date'] || $dt > new DateTime('today'))
            $err[] = 'Last donation date must be a valid past date.';
    }
    return [$d, $err];
}

// ---------- Authentication ----------
function is_logged_in(): bool { return !empty($_SESSION['user_id']); }

function require_login(): void {
    if (!is_logged_in()) {
        flash('Please log in as staff to continue.', 'error');
        redirect('login.php');
    }
}

function attempt_login(string $username, string $password): bool {
    $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $u = $stmt->fetch();
    // Always run password_verify to keep timing similar for unknown users.
    $hash = $u['password_hash'] ?? '$2y$10$usesomesillystringforsalt.invalidinvalidinvalidinvalidi';
    if (password_verify($password, $hash) && $u) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$u['id'];
        $_SESSION['username'] = $u['username'];
        return true;
    }
    usleep(500000); // slow down brute-force attempts
    return false;
}
