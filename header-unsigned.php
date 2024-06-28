<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($url))
    $url = "landing";

if (isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");
elseif (isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-dashboard");

require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/book-class.php';

$bookObj = new Book();

$bookList = $bookObj->fetchAllBooks();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Bookrack </title>

    <?php require_once __DIR__ . '/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/header-unsigned.css">
</head>

<body>
    <!-- header -->
    <header class="header w-100 border-bottom">
        <div class="container d-flex flex-row py-3 align-items-center justify-content-between header-container">
            <!-- logo -->
            <div class="d-flex flex-row gap-2 align-items-center header-logo pointer"
                onclick="window.location.href='/bookrack/landing'">
                <img src="/bookrack/assets/brand/bookrack-logo-black.png" alt="">
                <h3 class="m-0 fw-bold"> Bookrack </h3>
            </div>

            <div class="nav-container" id="menu">
                <nav class="d-flex gap-2 nav" id="main-nav-menu">
                    <!-- close close -->
                    <div class="justify-content-end px-5 py-4 close close-nav-div">
                        <i class="fa fa-multiply pointer fs-3" id="close-nav-menu"></i>
                    </div>

                    <ul class="d-flex">
                        <li> <a
                                href="<?= $url == 'landing' ? '#trending-book-section' : '/bookrack/landing/#trending-book-section' ?>">
                                Trending Books </a> </li>
                        <li> <a
                                href="<?= $url == 'landing' ? '#service-section' : '/bookrack/landing/#service-section' ?>">
                                Services </a> </li>
                        <li> <a href="<?= $url == 'landing' ? '#genre-section' : '/bookrack/landing/#genre-section' ?>">
                                Genres </a>
                    </ul>

                    <div class="signin-div">
                        <a href="/bookrack/signin" class="btn text-white py-2 px-4"> Signin </a>
                    </div>
                </nav>
            </div>

            <!-- menu and sign in button -->
            <div class="d-flex flex-row align-items-center menu-signin">
                <i class="fa fa-bars fs-3 pointer" id="open-nav-menu"></i>
                <a href="/bookrack/signin" class="btn text-white py-2 px-4"> Signin </a>
            </div>
        </div>
    </header>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/app/script-include.php'; ?>

    <!-- js :: current file -->
    <script>
        var menuState = false;
        const menu = document.getElementById("menu");
        const openMenu = document.getElementById("open-nav-menu");
        const closeMenu = document.getElementById("close-nav-menu");

        // open menu
        openMenu.addEventListener('click', function () {
            menuState = !menuState;
            menu.style = "right: 0; transition: .4s";
        });

        // close menu
        closeMenu.addEventListener('click', function () {
            menuState = !menuState;
            menu.style = "right: -100%; transition: .4s";
        });

        // device width changing
        widthCheck = () => {
            if (window.innerWidth < 768) {
                if (!menuState) {
                    // menu.style = "right: -100%; transition: 0s";
                } else {
                    // menu.style = "right: 0; transition: 0.4s";
                }
            }
        }

        window.addEventListener('resize', widthCheck);

        widthCheck();
    </script>
</body>

</html>