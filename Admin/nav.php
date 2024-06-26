<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

require_once __DIR__ . '/../../bookrack/admin/app/admin-class.php';
require_once __DIR__ . '/../../bookrack/app/functions.php';

$asideAdmin = new Admin();

$asideAdmin->setId($_SESSION['bookrack-admin-id']);
$asideAdmin->fetch($_SESSION['bookrack-admin-id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Nav </title>

    <?php require_once __DIR__ . '/../../bookrack/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/nav.css">
</head>

<body>
    <!-- nav -->
    <aside class="aside">
        <!-- aside container -->
        <div class="d-flex flex-column gap-5 gap-lg-1 mt-5 aside-container">
            <!-- profile -->
            <div class="d-lg-flex flex-column d-none profile-container gap-2">
                <!-- profile container -->
                <div class="profile-photo rounded-circle">
                    <img src="<?php if ($asideAdmin->getProfilePicture() != "") {
                        echo $asideAdmin->getProfilePictureImageUrl();
                    } else {
                        echo '/bookrack/assets/images/blank-user.jpg';
                    } ?>" alt="" id="admin-profile-photo" loading="lazy">
                </div>

                <!-- profile detail -->
                <div class="d-flex flex-column align-items-center profile-details">
                    <p class="f-reset" id="username">
                        <?php
                        if ($asideAdmin->getFirstName() != "") 
                            echo ucfirst($asideAdmin->getFirstName());
                        ?>
                    </p>
                    <p class="f-reset" id="email-address"> admin@gmail.com </p>
                </div>
            </div>

            <!-- menu -->
            <i class="fa fa-bars text-light d-block d-lg-none pointer nav-menu-bar"></i>

            <!-- nav -->
            <nav class="nav px-lg-2">
                <ul class="d-flex flex-column p-0">
                    <!-- dashboard -->
                    <li onclick="window.location.href='/bookrack/admin/admin-dashboard'">
                        <i class="fa-brands fa-windows nav-icon"></i>
                        <span class="d-none d-lg-block"> Dashboard </span>
                    </li>

                    <!-- profile -->
                    <li onclick="window.location.href='/bookrack/admin/admin-profile'">
                        <i class="fa fa-user nav-icon"></i>
                        <span class="d-none d-lg-block"> My Profile </span>
                    </li>

                    <!-- notification -->
                    <li onclick="window.location.href='/bookrack/admin/admin-notification'">
                        <i class="fa-regular fa-bell nav-icon"></i>
                        <span class="d-none d-lg-block"> Notification </span>
                    </li>

                    <!-- users -->
                    <li onclick="window.location.href='/bookrack/admin/admin-users'">
                        <i class="fa fa-users nav-icon"></i>
                        <span class="d-none d-lg-block"> Users </span>
                    </li>

                    <!-- books -->
                    <li onclick="window.location.href='/bookrack/admin/admin-books'">

                        <i class="fa fa-book nav-icon"></i>
                        <span class="d-none d-lg-block"> Books </span>

                    </li>


                    <!-- offers -->
                    <li onclick="window.location.href='/bookrack/admin/admin-book-offers'">
                        <i class="fa fa-hands nav-icon"></i>
                        <span class="d-none d-lg-block"> Offers </span>
                    </li>

                    <!-- request -->
                    <li onclick="window.location.href='/bookrack/admin/admin-book-requests'">
                        <i class="fa-solid fa-comment-dots nav-icon"></i>
                        <span class="d-none d-lg-block"> Requests </span>
                    </li>

                    <!-- request -->
                    <li onclick="window.location.href='/bookrack/admin/admin-rent-history'">
                        <i class="fa-regular fa-note-sticky nav-icon"></i>
                        <span class="d-none d-lg-block"> Rent History </span>
                    </li>

                    <!-- signout -->
                    <li class="aside-menu-li" onclick="window.location.href='/bookrack/admin/app/admin-signout.php'">
                        <i class="fa-solid fa-right-from-bracket nav-icon"></i>
                        <span class="d-none d-lg-block"> Sign out </span>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../../bookrack/app/script-include.php'; ?>
</body>

</html>