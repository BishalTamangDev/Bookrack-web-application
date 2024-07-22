<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-signin");

if (!isset($nav))
    $nav = "dashboard";

require_once __DIR__ . '/../app/functions.php';

if (!isset($adminId)) {
    require_once __DIR__ . '/app/admin-class.php';

    $adminId = $_SESSION['bookrack-admin-id'];

    $profileAdmin = new Admin();
    $adminExists = $profileAdmin->checkAdminExistenceById($adminId);

    if (!$adminExists)
        header("Location: /bookrack/admin/app/admin-signout.php");
}

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

    <?php require_once __DIR__ . '/../app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/admin/nav.css">
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
                    <li onclick="window.location.href='/bookrack/admin/admin-dashboard'"
                        class="<?php if ($nav == "dashboard")
                            echo "active"; ?>">
                        <i class="fa-brands fa-windows nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "dashboard")
                            echo "text-dark"; ?>"> Dashboard
                        </span>
                    </li>

                    <!-- users -->
                    <li onclick="window.location.href='/bookrack/admin/admin-users'"
                        class="<?php if ($nav == "users" || $nav == "user-details")
                            echo "active"; ?>">
                        <i class="fa fa-users nav-icon"></i>
                        <span
                            class="d-none d-lg-block <?php if ($nav == "users" || $nav == "user-details")
                                echo "text-dark"; ?>">
                            Users </span>
                    </li>

                    <!-- books -->
                    <li onclick="window.location.href='/bookrack/admin/admin-books'"
                        class="<?php if ($nav == "books" || $nav == "book-details")
                            echo "active"; ?>">

                        <i class="fa fa-book nav-icon"></i>
                        <span
                            class="d-none d-lg-block <?php if ($nav == "books" || $nav == "book-details")
                                echo "text-dark"; ?>">
                            Books </span>

                    </li>

                    <!-- request -->
                    <li onclick="window.location.href='/bookrack/admin/admin-book-requests'"
                        class="<?php if ($nav == "requests" || $nav == "request-details")
                            echo "active"; ?>">
                        <i class="fa-solid fa-comment-dots nav-icon"></i>
                        <span
                            class="d-none d-lg-block <?php if ($nav == "requests" || $nav == "request-details")
                                echo "text-dark"; ?>">
                            Requests </span>
                    </li>

                    <!-- rent history -->
                    <li onclick="window.location.href='/bookrack/admin/admin-rent-history'"
                        class="<?php if ($nav == "rent-history")
                            echo "active"; ?>">
                        <i class="fa-regular fa-note-sticky nav-icon"></i>
                        <span class="d-none d-lg-block <?php if ($nav == "rent-history")
                            echo "text-dark"; ?>"> Rent
                            History </span>
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
                    <form
                    <?php
                    if(isset($url)) {
                        if($url == "users") {
                            ?>
                            action="/bookrack/admin/admin-users"
                            <?php
                        } elseif($url == "books") {
                            ?>
                            action="/bookrack/admin/admin-books"
                            <?php
                        } elseif($url == "book-requests") {
                            ?>
                            action="/bookrack/admin/admin-book-requests"
                            <?php
                        }
                    }
                    ?>
                     class="d-flex flex-row search-form">
                        <div class="input-group">
                            <input type="search" name="admin-search-content" class="form-control m-0" id=""
                                value="<?php if(isset($_GET['admin-search-content'])) echo $searchContent;?>" placeholder="search here" required>
                        </div>
                        <button type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
            </div>

            <!-- notification -->
            <div class="notification-container">
                <div class="d-flex flex-row gap-2 icon-count pointer" id="notification-trigger">
                    <i class="fa fa-bell fs-4"></i>
                    <div class="position-absolute notification-count-div text-align-center">
                        <p class="m-0 text-danger"> 9+ </p>
                    </div>
                </div>

                <div class="position-absolute bg-white notification-div" id="notification-container">
                    <div class="d-flex flex-row justify-content-between align-items-center p-2 py-2 heading-div">
                        <div class="title">
                            <p class="m-0 font-weight-bold"> Notifications </p>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="d-flex flex-column mt-2 pb-2 px-2 notifications">
                        <!-- notification 1 -->
                        <div class="d-flex flex-row gap-2 pointer p-2 notification">
                            <div class="d-flex flex-row justify-content-around align-items-center icon-div">
                                <img src="/bookrack/assets/icons/notification/book-added.png" alt="">
                            </div>

                            <div class="details">
                                <!-- notification detail -->
                                <div class="detail">
                                    <p class="m-0">
                                        Notification details appears here...
                                    </p>
                                </div>

                                <!-- date -->
                                <div class="date">
                                    <p class="m-0 small text-secondary">
                                        0000-00-00 00-00-00
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- profile -->
            <div class="position-relative profile-container">
                <!-- profile menu -->
                <div class="profile-image pointer" id="profile-menu-trigger">
                    <img src="/bookrack/assets/images/user-1.png" alt="">
                </div>

                <div class="position-absolute bg-white profile-menu-container" id="profile-menu-container">
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
    <?php require_once __DIR__ . '/../app/script-include.php'; ?>

    <!-- header script -->
    <script>
        var profileMenuState = false;
        var notificationState = false;

        const profileMenuTrigger = $('#profile-menu-trigger');
        const notificationTrigger = $('#notification-trigger');

        const profileMenu = $('#profile-menu-container');
        const notificationContainer = $('#notification-container');

        // notification trigger
        notificationTrigger.on('click', function () {
            notificationState = !notificationState;
            toggleNotification();
        });

        // profile menu trigger
        profileMenuTrigger.on('click', function () {
            profileMenuState = !profileMenuState;
            toggleProfileMenu();
        });

        // function to toggle notification container
        toggleNotification = () => {
            if (notificationState) {
                if (profileMenuState) {
                    profileMenuState = !profileMenuState;
                    toggleProfileMenu();
                }
                notificationContainer.show();
            } else {
                notificationContainer.hide();
            }
        }

        // function to toggle profile menu
        toggleProfileMenu = () => {
            if (profileMenuState) {
                if (notificationState) {
                    notificationState = !notificationState;
                    toggleNotification();
                }
                profileMenu.show();
            } else {
                profileMenu.hide();
            }
        }

        toggleNotification();
        toggleProfileMenu();
    </script>
</body>

</html>