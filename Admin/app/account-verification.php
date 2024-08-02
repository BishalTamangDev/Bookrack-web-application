<?php
$message = "";
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/");

require_once __DIR__ . '/../../classes/admin.php';

$admin = new Admin();

$message = $admin->verifyAdminAccount($_SESSION['bookrack-admin-id']);

echo $message;

exit;