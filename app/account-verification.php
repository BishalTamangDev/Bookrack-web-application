<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

// Validate CSRF token
if ($_POST['csrf_token_account_verification'] !== $_SESSION['csrf_token']) {
    echo 'Invalid CSRF token.';
    exit;
}

require_once __DIR__ . '/../classes/user.php';

$status = false;
$user = new User();
$status = $user->accountVerification($_SESSION['bookrack-user-id']);
echo $status ? "success" : "failure";
exit;