<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

$userId = $_SESSION['bookrack-user-id'] ?? 0;

if ($userId == 0) {
    echo false;
    exit;
}

// Validate CSRF token
if ($_POST['csrf_token_account_verification'] !== $_SESSION['csrf_token']) {
    echo false;
    exit;
}

require_once __DIR__ . '/../classes/user.php';
require_once __DIR__ . '/../classes/notification.php';

$tempUser = new User();
$tempNotification = new Notification();

$status = $tempUser->applyForVerification($_SESSION['bookrack-user-id']);

if ($status) {
    // notification
    $status = $tempNotification->applyForAccountVerification($userId);
}

echo $status ? true : false;

exit;