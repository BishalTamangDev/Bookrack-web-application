<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "book-request-details";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/../classes/admin.php';
require_once __DIR__ . '/../functions/genre-array.php';

$profileAdmin = new Admin();
$adminExists = $profileAdmin->checkAdminExistenceById($adminId);

if (!$adminExists)
    header("Location: /bookrack/admin/app/admin-signout.php");

if ($profileAdmin->accountStatus != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Book Request Detail </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/admin.css">
</head>

<body>
    <!-- nav -->
    <?php require_once __DIR__ . '/nav.php'; ?>

    <!-- main -->
    <main class="main">
        <p class="page-heading"> Book Request Details </p>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>
</body>

</html>