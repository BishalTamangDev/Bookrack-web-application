<?php

if (!isset($nav))
    $nav = "dashboard";

// searching
$search = false;
if (isset($_GET['admin-search-content'])) {
    $search = true;
    $searchContent = $_GET['admin-search-content'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Nav </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/admin/nav.css">
</head>

<body>
    <!-- nav -->
    <aside class="aside">
        <!-- aside container -->
        <div class="d-flex flex-column gap-5 gap-lg-1 mt-5 aside-container">
            <!-- menu -->
            <!-- <i class="fa fa-bars text-light d-block d-lg-none pointer nav-menu-bar"></i> -->

            <!-- nav -->
            <nav class="nav px-lg-2">
                <ul class="d-flex flex-column p-0">
                    <!-- dashboard -->
                    <li onclick="window.location.href='/bookrack/admin/admin-dashboard'" class="<?php if ($nav == "dashboard")
                        echo "active"; ?>">
                        <i class="fa-brands fa-windows nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "dashboard")
                            echo "text-dark"; ?>"> Dashboard
                        </span>
                    </li>

                    <!-- users -->
                    <li onclick="window.location.href='/bookrack/admin/admin-users'" class="<?php if ($nav == "users" || $nav == "user-details")
                        echo "active"; ?>">
                        <i class="fa fa-users nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "users" || $nav == "user-details")
                            echo "text-dark"; ?>">
                            Users </span>
                    </li>

                    <!-- books -->
                    <li onclick="window.location.href='/bookrack/admin/admin-books'" class="<?php if ($nav == "books" || $nav == "book-details")
                        echo "active"; ?>">
                        <i class="fa fa-book nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "books" || $nav == "book-details")
                            echo "text-dark"; ?>">
                            Books </span>
                    </li>

                    <!-- order summary -->
                    <li onclick="window.location.href='/bookrack/admin/admin-orders'" class="<?php if ($nav == "orders" || $nav == "order-summary")
                        echo "active"; ?>">
                        <i class="fa-solid fa-cart-shopping nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "orders" || $nav == "order-summary")
                            echo "text-dark"; ?>">
                            Orders </span>
                    </li>

                    <!-- arrival -->
                    <li onclick="window.location.href='/bookrack/admin/admin-arrivals'" class="<?php if ($nav == "arrival")
                        echo "active"; ?>">
                        <i class="fa-solid fa-arrow-right-to-bracket nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "arrival")
                            echo "text-dark"; ?>">
                            Arrivals </span>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <header class="bg-white border-bottom align-items-center justify-content-between header header-container">
        <!-- heading -->
        <p class="m-0 fs-3 heading"> </p>

        <div class="d-flex flex-row gap-4 align-items-center justify-content-between form-notification-profile">
            <!-- search form -->
            <div class="d-flex flex-row gap-2 search-form-container">
                <?php
                $eligiblePageList = ["users", "books", "arrivals"];
                $visibilityClass = !in_array($page, $eligiblePageList) ? 'd-none' : '';
                ?>
                <form class="<?= $visibilityClass ?> d-flex flex-row search-form" id="search-form">
                    <div class="input-group">
                        <input type="search" name="admin-search-content" class="m-0 rounded" id="admin-search-content"
                            value="<?php if (isset($_GET['admin-search-content']))
                                echo $searchContent; ?>" placeholder="search here" required>
                    </div>
                </form>
            </div>

            <!-- notification -->
            <div class="notification-container" id="notification-main-container">
                <div class="d-flex flex-row gap-2 icon-count pointer" id="notification-trigger">
                    <i class="fa fa-bell fs-5"></i>
                    <div class="position-absolute notification-count-div text-align-center">
                        <p class="m-0 text-danger" id="notification-count"> </p>
                    </div>
                </div>

                <div class="invisible position-absolute bg-white p-0 notification-box" id="notification-box">
                    <div class="d-flex flex-row justify-content-between align-items-center w-100 p-3 py-3 heading-div">
                        <p class="m-0 fw-bold fs-4"> Notifications </p>
                        <i class="fa fa-multiply fs-3 pointer" id="notification-trigger-close"></i>
                    </div>

                    <div class="d-flex flex-column notifications" id="notifications">
                        <!-- empty -->
                        <div class="d-none invisible p-3 empty-notification">
                            <p class="m-0"> Empty! </p>
                        </div>

                        <!-- backup -->
                        <div class="d-none invisible notification">
                            <div class="icon-div">
                                <img src="/bookrack/assets/icons/notification/book-added.png" alt="">
                            </div>

                            <div class="details">
                                <!-- notification detail -->
                                <div class="detail">
                                    <p>
                                        Notification details appears here...
                                    </p>
                                </div>

                                <!-- date -->
                                <p class="date">
                                    0000-00-00 00-00-00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- profile -->
            <div class="position-relative profile-container">
                <!-- profile menu -->
                <div class="profile-image pointer" id="profile-menu-trigger">
                    <?php
                    if (!isset($photoUrl)) {
                        $profileAdmin->setPhotoUrl();
                    }

                    if ($profileAdmin->photoUrl != "") {
                        $photoUrl = $profileAdmin->photoUrl;
                    } else {
                        $photoUrl = "/bookrack/assets/images/blank-user.jpg";
                    }
                    ?>
                    <img src="<?= $photoUrl ?>" alt="">
                </div>

                <div class="invisible position-absolute bg-white profile-menu-container" id="profile-menu-container">
                    <ul class="d-flex flex-column profile-menu m-0 p-0">
                        <li onclick="window.location.href='/bookrack/admin/admin-profile'">
                            <i class="fa fa-user"> </i>
                            <span> My Profile </span>
                        </li>
                        <!-- <hr class="p-0 m-0"> -->
                        <li onclick="window.location.href='/bookrack/admin/app/admin-signout.php'">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span> Sign out </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <!-- notification click -->
    <script src="/bookrack/js/notification-click.js"></script>
    
    <!-- header script -->
    <script>
        $(document).ready(function () {
            let profileMenuState = false;
            let notificationState = false;

            // function to toggle notification container
            function toggleNotification() {
                if (notificationState) {
                    if (profileMenuState) {
                        profileMenuState = !profileMenuState;
                        toggleProfileMenu();
                    }
                    $('#notification-box').removeClass("invisible");
                } else {
                    $('#notification-box').addClass("invisible");
                }
            }

            // function to toggle profile menu
            function toggleProfileMenu() {
                if (profileMenuState) {
                    if (notificationState) {
                        notificationState = !notificationState;
                        toggleNotification();
                    }
                    $('#profile-menu-container').removeClass("invisible");
                } else {
                    $('#profile-menu-container').addClass("invisible");
                }
            }

            function fetchNotification() {
                $.ajax({
                    url: '/bookrack/admin/sections/header-notification.php',
                    type: "POST",
                    beforeSend: function () {
                        $('#notifications').html("<div class='p-3 d-flex flex-row gap-3'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width:26px;'> <p class='m-0'> Loading notification... </p> </div>");
                    },
                    success: function (data) {
                        $('#notifications').html(data);
                    },
                });
            }

            function countUnseenNotification() {
                $.ajax({
                    url: "/bookrack/admin/app/count-unseen-admin-notification.php",
                    success: function (data) {
                        $('#notification-count').html(data);
                    }
                });
            }

            setInterval(function () {
                countUnseenNotification();
            }, 3000);

            // notification trigger
            $(document).on('click', '#notification-trigger', function () {
                notificationState = !notificationState;
                toggleNotification();

                // fetch notification
                fetchNotification();
            });

            $(document).on('click', '#notification-trigger-close', function () {
                notificationState = !notificationState;
                toggleNotification();
            });

            // profile menu trigger
            $(document).on('click', '#profile-menu-trigger', function () {
                profileMenuState = !profileMenuState;
                toggleProfileMenu();
            });
        });
    </script>
</body>

</html>