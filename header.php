<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// redirect to the landing page if no signed in
if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");

$userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/../bookrack/app/user-class.php';

$headerProfile = new User();

$userExists = $headerProfile->fetch($userId);

if(!$userExists)
    header("Location: /bookrack/signin");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Header </title>

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="/bookrack/assets/brand/brand-logo.png">

    <!-- font awesome :: cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- bootstrap css :: cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- bootstrap css :: local file -->
    <link rel="stylesheet" href="/bookrack/assets/css/bootstrap-css-5.3.3/bootstrap.min.css">

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/header.css">
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
                <form action="" class="search-form">
                    <input type="search" name="search-content" id="search" placeholder="search here" class="p-2"
                        required>
                </form>

                <!-- add book -->
                <div class="d-flex flex-row align-items-center gap-2 pointer justify-content-center p-2 px-3 text-white add-book"
                    onclick="window.location.href='/bookrack/add-book'">
                    <i class="fa fa-add"></i>
                    <span style="white-space: nowrap;"> ADD BOOK </span>
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

                <!-- profile menu -->
                <div class="position-relative profile-div">
                    <div class="d-none d-md-block profile-photo" id="profile-menu-trigger">
                        <?php
                        if ($headerProfile->getProfilePicture() != "") {
                            ?>
                            <img src="<?= $headerProfile->getProfilePictureImageUrl() ?>" alt="" class="pointer">
                            <?php
                        } else {
                            ?>
                            <img src="/bookrack/assets/images/blank-user.jpg" alt="" class="pointer">
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
                            <li onclick="window.location.href='/bookrack/signout'"> <i class="fa fa-sign-out"></i>
                                <span>Sign Out</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <script>
        // menu
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");

        const menu = document.getElementById("menu");

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
            if (profileMenuState) {
                profileMenu.style.display = "block";
            } else {
                profileMenu.style.display = "none";
            }
        });

        // device width changing
        widthCheck = () => {
            if (window.innerWidth < 768) {
                // profileMenuState = true;
            } else {
                profileMenuState = false;
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