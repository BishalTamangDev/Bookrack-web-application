<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

require_once __DIR__ . '/connection.php';

global $database;
$status = 0;

$userId = $_SESSION['bookrack-user-id'];

$properties['account_status'] = "verified";

$response = $database->getReference("users/{$userId}")->update($properties);

if ($response)
    $status = 1;

$_SESSION['status'] = $status;
$_SESSION['status-message'] = $status ? "Your account has been verified." : "Your account couldn't be verified.";

header("Location: /bookrack/profile/view-profile");
exit();
