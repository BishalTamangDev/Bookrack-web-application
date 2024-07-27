<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// redirect to the landing page if no signed in
if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

if (!isset($userId))
    $userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/../classes/user.php';

if (!isset($profileUser)) {
    $profileUser = new User();
    $userExists = $profileUser->checkUserExistenceById($userId);

    if (!$userExists)
        header("Location: /bookrack/signin");
}

// search
if (!isset($searchState))
    $searchState = isset($_GET['search-content']) && $_GET['search-content'] != '' ? true : false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Header </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/header.css">
</head>

<body>
    <header class="header w-100 border-bottom">
        <div class="container d-flex flex-row p-3 align-items-center justify-content-between header-container">
            <!-- logo -->
            <div class="d-flex flex-row gap-2 align-items-center header-logo pointer"
                onclick="window.location.href='/bookrack/home'">
                <img src="/bookrack/assets/brand/bookrack-logo-black.png" alt="">
                <h3 class="f-reset fw-bold"> Bookrack </h3>
            </div>

            <!-- menu bar -->
            <i class="fa fa-bars pointer fs-3 d-md-none" id="open-menu"></i>

            <!-- menu -->
            <div class="flex-column d-md-flex flex-md-row gap-3 p-4 p-md-0 align-items-center menu" id="menu">
                <!-- close menu -->
                <div class="d-flex d-md-none justify-content-end container p-3 close" id="close-menu">
                    <i class="fa fa-multiply pointer fs-3"></i>
                </div>

                <!-- search -->
                <form method="GET" action="/bookrack/app/search-code.php" class="search-form">
                    <input type="search" name="search-content" id="search" placeholder="search here" class="p-2" value="<?php
                    if ($searchState) {
                        echo $searchContent;
                    }
                    ?>" required>
                </form>

                <!-- add book -->
                <div class="d-flex flex-row align-items-center gap-2 pointer justify-content-center p-2 px-3 text-white add-book"
                    onclick="window.location.href='/bookrack/add-book'">
                    <i class="fa fa-add"></i>
                    <span style="white-space: nowrap;"> ADD BOOK </span>
                </div>

                <!-- extra: my profile -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href='/bookrack/profile'">
                    <i class="fa fa-user"></i>
                    <span> My Profile </span>
                </div>

                <!-- extra: notification -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href=''">
                    <i class="fa fa-bell"></i>
                    <span> Notification </span>
                </div>

                <!-- extra: my books -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href='/profile/my-books'">
                    <i class="fa fa-book"></i>
                    <span> My Books </span>
                </div>

                <!-- wishlist -->
                <div class="d-flex flex-row align-items-center justify-content-center gap-2 border p-2 rounded pointer wishlist"
                    onclick="window.location.href='/bookrack/profile/wishlist'">
                    <i class="fa fa-bookmark"></i>
                    <span> Wishlist </span>
                </div>

                <!-- cart -->
                <div class="d-flex flex-row align-items-center justify-content-center gap-2 border p-2 rounded pointer cart"
                    onclick="window.location.href='/bookrack/cart'">
                    <i class="fa fa-shopping-cart"></i>
                    <span> Cart </span>
                </div>

                <!-- notification -->
                <div class="position-relative notification-section" id="notification-trigger">
                    <div class="position-relative icon-container">
                        <i class="fa fa-bell fs-5 pointer"></i>
                        <b>
                            <p class="position-absolute m-0 notification-counter pointer"> 9+ </p>
                        </b>
                    </div>

                    <!-- notification section -->
                    <div class="position-absolute bg-white rounded p-0 notification-main-container"
                        id="notification-main-container">
                        <div class="top p-2">
                            <p class="m-0 p-0 heading fs-5"> Notification </p>
                        </div>

                        <hr class="m-0 p">

                        <!-- notification container -->
                        <div class="d-flex flex-column px-1 notification-container">
                            <!-- notification 1 -->
                            <div class="d-flex flex-row gap-2 pointer notification unclicked-notification">
                                <!-- icon -->
                                <div
                                    class="d-flex flex-row align-items-center justify-content-around notification-icon">
                                    <img src="/bookrack/assets/icons/notification/book-added.png" alt="">
                                </div>

                                <!-- details -->
                                <div class="details">
                                    <div class="details">
                                        <p class="m-0"> Notification details appears here... </p>
                                    </div>

                                    <div class="date">
                                        <p class="m-0 small text-secondary">
                                            0000-00-00 00-00
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- extra: my books -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href='/bookrack/app/signout.php'">
                    <i class="fa fa-sign-out"></i>
                    <span> Sign out </span>
                </div>

                <!-- profile menu -->
                <div class="position-relative profile-div">
                    <div class="d-none d-md-block profile-photo" id="profile-menu-trigger">
                        <?php
                        if ($profileUser->photo != " ") {
                            $profileUser->fetchUserPhotoUrl();
                            $userPhotoUrl = $profileUser->photoUrl;
                            ?>
                            <img src="<?= $userPhotoUrl ?>" alt="" class="pointer bg-light" loading="lazy">
                            <?php
                        } else {
                            ?>
                            <img src="/bookrack/assets/images/blank-user.jpg" alt="" class="pointer bg-light"
                                loading="lazy">
                            <?php
                        }
                        ?>
                    </div>

                    <div class="position-absolute profile-menu" id="profile-menu">
                        <ul>
                            <li onclick="window.location.href='/bookrack/profile'"> <i class="fa fa-user"></i> <span>My
                                    Profile</span> </li>
                            <li onclick="window.location.href='/bookrack/profile/my-books'"> <i class="fa fa-book"></i>
                                <span>My Books</span>
                            </li>
                            <li onclick="window.location.href='/bookrack/profile/earning'"> <i class="fa fa-dollar"></i>
                                <span>Earning</span>
                            </li>
                            <li onclick="window.location.href='/bookrack/app/signout.php'"> <i
                                    class="fa fa-sign-out"></i>
                                <span>Sign Out</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <script>
        // menu
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");

        const menu = document.getElementById("menu");
        var notificationState = false;
        const notificationTrigger = $('#notification-trigger');
        const notificationContainer = $('#notification-main-container');

        // profile menu
        var profileMenuState = false;
        const profileMenu = document.getElementById("profile-menu");
        const profileMenuTrigger = document.getElementById("profile-menu-trigger");

        // open menu
        openMenu.addEventListener('click', function () {
            menu.style.display = "flex";
            menu.style = "right: 0; transition: .4s";
        });

        // close menu
        closeMenu.addEventListener('click', function () {
            menu.style.display = "none";
            menu.style = "right: -100%; transition: .4s";
        });

        // profile menu
        profileMenuTrigger.addEventListener('click', function () {
            profileMenuState = !profileMenuState;
            toggleProfileMenu();
        });

        toggleProfileMenu = () => {
            if (profileMenuState) {
                if (notificationState) {
                    notificationState = !notificationState;
                    toggleNotification();
                }
                profileMenu.style.display = "block";
            } else {
                profileMenu.style.display = "none";
            }
        }

        // notification
        notificationTrigger.on('click', function () {
            notificationState = !notificationState;
            toggleNotification();
        });

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

        toggleNotification();

        // device width changing
        widthCheck = () => {
            if (window.innerWidth < 768) {
                // profileMenuState = true;
            } else {
                profileMenuState = false;
                notificationState = false;
                toggleNotification();
            }
            if (window.innerWidth < 1188) {
                // user menu
                if (profileMenuState) {
                    profileMenu.style.display = "block";
                } else {
                    profileMenu.style.display = "none";
                }
            }
        }

        window.addEventListener('resize', widthCheck);
        menu.style.display = "none";
        profileMenu.style.display = "none";
        widthCheck();
    </script>
</body>

</html>