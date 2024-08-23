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
} else {
    $profileUser->fetchUserPhotoUrl();
    $userPhotoUrl = $profileUser->photoUrl;
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
    <link rel="stylesheet" href="/bookrack/css/header-notification.css">
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
            <div class="d-flex flex-row gap-3">
                <!-- menu bar -->
                <i class="fa fa-bars pointer fs-3 d-md-none" id="open-menu"></i>
            </div>

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
                    data-bs-toggle="modal" data-bs-target="#notification-modal" id="notification-trigger-secondary">
                    <i class="fa fa-bell"></i>
                    <span> Notification </span>
                    <div>
                        <p class="m-0 text-danger fs-4 fw-semibold" id="notification-count-secondary"> </p>
                    </div>
                </div>

                <!-- extra: my books -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href='/bookrack/my-books'">
                    <i class="fa fa-book"></i>
                    <span> My Books </span>
                </div>

                <!-- extra: requests -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href='/bookrack/requests'">
                    <i class="fa fa-book"></i>
                    <span> Requests </span>
                </div>

                <!-- wishlist -->
                <div class="d-flex flex-row align-items-center justify-content-center gap-2 border p-2 rounded pointer wishlist"
                    onclick="window.location.href='/bookrack/wishlist'">
                    <i class="fa fa-bookmark"></i>
                    <span> Wishlist </span>
                </div>

                <!-- cart -->
                <div class="d-flex flex-row align-items-center justify-content-center gap-2 border p-2 rounded pointer cart"
                    onclick="window.location.href='/bookrack/cart'">
                    <i class="fa fa-shopping-cart"></i>
                    <span> Cart </span>
                </div>

                <!-- extra: my books -->
                <div class="align-items-center justify-content-center gap-2 border p-2 rounded pointer cart extra"
                    onclick="window.location.href='/bookrack/app/signout.php'">
                    <i class="fa fa-sign-out"></i>
                    <span> Sign out </span>
                </div>

                <!-- notification section -->
                <div class="d-none d-md-flex flex-row gap-2 align-items-center">
                    <div class="position-relative notification-container" id="notification-main-container">
                        <div class="d-flex flex-row gap-2 pointer icon-count" id="notification-trigger">
                            <i class="fa fa-bell fs-5"></i>
                            <div class="position-absolute notification-count-div text-align-center">
                                <p class="m-0 text-danger fw-semibold" id="notification-count"> </p>
                            </div>
                        </div>

                        <div class="invisible position-absolute bg-white p-0 notification-box" id="notification-box">
                            <div
                                class="d-flex flex-row justify-content-between align-items-center w-100 p-3 py-3 heading-div">
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
                </div>

                <!-- profile menu -->
                <div class="position-relative profile-div">
                    <div class="d-none d-md-block profile-photo" id="profile-menu-trigger">
                        <?php
                        if ($profileUser->photo != " " && $profileUser->photo != "") {
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
                            <li onclick="window.location.href='/bookrack/my-books'"> <i class="fa fa-book"></i>
                                <span> My Books </span>
                            </li>
                            <li onclick="window.location.href='/bookrack/requests'"> <i class="fa fa-book"></i>
                                <span> Requests </span>
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

    <!-- Modal -->
    <div class="modal fade" id="notification-modal" tabindex="-1" aria-labelledby="notification-modal-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3 fw-semibold" id="notification-modal-label"> Notifications </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0" id="notifications-modal">

                </div>
            </div>
        </div>
    </div>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../includes/script.php'; ?>

    <!-- notification click -->
    <script src="/bookrack/js/notification-click.js"></script>

    <script>
        // menu
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");

        const menu = document.getElementById("menu");
        var notificationState = false;
        const notificationBox = $('#notification-box');
        const notificationModal = $('#notification-modal');

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


        toggleNotification = () => {
            if (notificationState) {
                if (profileMenuState) {
                    profileMenuState = !profileMenuState;
                    toggleProfileMenu();
                }
                notificationBox.removeClass('invisible');
            } else {
                notificationBox.addClass('invisible');
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

        $(document).ready(function () {
            function countUnseenNotification() {
                $.ajax({
                    type: "POST",
                    url: "/bookrack/app/count-unseen-notification.php",
                    data: { userId: '<?= $userId ?>' },
                    success: function (data) {
                        $('#notification-count').html(data);
                        $('#notification-count-secondary').html(data);
                    }
                });
            }

            function fetchNotification(which) {
                notificationState = !notificationState;
                toggleNotification();
                $.ajax({
                    type: "POST",
                    url: "/bookrack/sections/header-notification.php",
                    data: { userId: '<?= $userId ?>' },
                    beforeSend: function () {
                        if (which == 'primary') {
                            $('#notifications').html("<div class='p-3 d-flex flex-row gap-3'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width:26px;'> <p class='m-0'> Loading notification... </p> </div>");
                        } else {
                            $('#notifications-modal').html("<div class='p-3 d-flex flex-row gap-3'> <img src='/bookrack/assets/gif/filled-fading-balls.gif' style='width:26px;'> <p class='m-0'> Loading notification... </p> </div>");
                        }
                    },
                    success: function (data) {
                        if (which == 'primary') {
                            $('#notifications').html(data);
                        } else {
                            $('#notifications-modal').html(data);
                        }
                    },
                });
            }

            setInterval(function () {
                countUnseenNotification();
            }, 3000);


            $('#notification-trigger').click(function () {
                fetchNotification('primary');
            });

            // notification modal
            $('#notification-trigger-secondary').click(function () {
                fetchNotification('secondary');
            });

            $(document).on('click', '#notification-trigger-close', function () {
                notificationState = !notificationState;
                toggleNotification();
            });
        });
    </script>
</body>

</html>