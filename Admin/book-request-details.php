<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

$url = "book-request-details";
$adminId = $_SESSION['bookrack-admin-id'];

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();
$profileAdmin->fetch($adminId);

if ($profileAdmin->getAccountStatus() != "verified")
    header("Location: /bookrack/admin/admin-profile");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Book Request Detail </title>

    <?php require_once __DIR__ . '/../../bookrack/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
</head>

<body>
    <!-- nav -->
    <?php include 'nav.php'; ?>

    <!-- main -->
    <main class="main">
        <p class="page-heading"> Book Request Details </p>
    </main>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../../bookrack/app/script-include.php'; ?>
</body>

</html>