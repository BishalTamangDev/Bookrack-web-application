<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/");

require_once __DIR__ . '/../../app/connection.php';

global $database;
$status = 0;

$adminId = $_SESSION['bookrack-admin-id'];

$properties['account_status'] = "verified";

$response = $database->getReference("admins/{$adminId}")->update($properties);

if ($response)
    $status = 1;

$_SESSION['status'] = $status;
$_SESSION['status-message'] = $status ? "Your account has been verified." : "Your account couldn't be verified.";

header("Location: /bookrack/admin/admin-profile");
exit();
