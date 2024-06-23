<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['bookrack-admin-id'])){
    header("Location: /bookrack/admin/admin-signin");
}

// fetching the admin profile details
require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$profileAdmin = new Admin();

$profileAdmin->setId($_SESSION['bookrack-admin-id']);
$profileAdmin->fetch($profileAdmin->getId());

if($profileAdmin->getAccountStatus() != "verified"){
    header("Location: /bookrack/admin/admin-profile");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Book Offer Detail </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- local bootstrap -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/admin/admin.css">
</head>

<body>
    <!-- nav -->
    <?php
    include 'nav.php';
    ?>

    <!-- main -->
    <main class="main">
        <p class="page-heading"> Book Offer Details </p>
    </main>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"> </script>

    <!-- local bootstrap js -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.js"> </script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>
</body>

</html>