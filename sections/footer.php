<?php

require_once __DIR__ . '/../classes/genre.php';

if (!isset($genreObj))
    $genreObj = new Genre();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Footer </title>

    <?php require_once __DIR__ . '/../includes/header.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/css/footer.css">
</head>

<body>
    <!-- footer -->
    <footer class="d-flex flex-column gap-4 section footer">
        <div class="d-flex flex-column container gap-5 footer-container">
            <!-- main footer -->
            <div class="d-flex flex-row justify-content-lg-between gap-5 flex-wrap main-footer">
                <!-- physical location -->
                <div class="d-flex flex-column physical-location-section">
                    <!-- title -->
                    <p class="m-0 fw-bold footer-title"> Our Physical Location </p>

                    <!-- content -->
                    <div class="d-flex flex-row gap-3 content">
                        <div class="left">
                            <i class="fa fa-map-pin"></i>
                        </div>

                        <div class="d-flex flex-column right">
                            <p class="m-0 shop-name"> Bookrack Book Shop </p>
                            <p class="m-0 shop-location"> Bansbari, Kathmandu </p>
                        </div>
                    </div>
                </div>

                <!-- quick links -->
                <div class="main-footer-content-section">
                    <!-- title -->
                    <p class="m-0 fw-bold footer-title"> Quick Links </p>

                    <!-- content -->
                    <div class="d-flex flex-row content">
                        <ul list-style-type="none">
                            <li><a href=""> Offer book </a></li>
                            <li><a href=""> Rent out the book </a></li>
                            <li><a href=""> New Arrivals </a></li>
                            <li><a href=""> Blogs </a></li>
                            <li><a href=""> Shipping Details </a></li>
                        </ul>
                    </div>
                </div>

                <!-- about -->
                <div class="main-footer-content-section">
                    <!-- title -->
                    <p class="m-0 fw-bold footer-title"> About Us </p>
                    <!-- content -->
                    <div class="d-flex flex-row gap-2 content">
                        <ul>
                            <li><a href=""> Company </a></li>
                            <li><a href=""> Features </a></li>
                            <li><a href=""> Objectives </a></li>
                            <li><a href=""> Contact </a></li>
                        </ul>
                    </div>
                </div>

                <!-- popular genres -->
                <div class="main-footer-content-section">
                    <!-- title -->
                    <p class="m-0 fw-bold footer-title"> Popular Genres </p>
                    <!-- content -->
                    <div class="d-flex flex-row gap-2 content">
                        <ul>
                            <?php
                            if (!isset($genreList)){
                                $genreList = $genreObj->fetchGenreList();
                            }
                            ?>

                            <?php
                            if (sizeof($genreList) != 0) {

                                foreach ($genreList as $genre) {
                                    ?>
                                    <li> <?= $genre ?> </a></li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li> No trending genre yet! </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- mobile application -->
                <div class="d-flex flex-column mobile-app-section gap-2">
                    <!-- title -->
                    <p class="m-0 fw-bold footer-title"> Get Bookrack in <br> Your Pocket </p>
                    <!-- content -->
                    <div class="d-flex flex-column gap-2 content-container">
                        <!-- android -->
                        <div class="content pointer">
                            <div class="left">
                                <i class="fa-brands fa-google-play"></i>
                            </div>
                            <div class="right">
                                <p class="m-0"> GET IT ON </p>
                                <p class="m-0"> Google Play </p>
                            </div>
                        </div>

                        <!-- ios -->
                        <div class="content pointer">
                            <div class="left">
                                <i class="fa-brands fa-apple text-light"></i>
                            </div>
                            <div class="right">
                                <p class="m-0"> GET IT ON </p>
                                <p class="m-0"> App Store </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- social media connection -->
            <div class="d-flex flex-column align-items-center gap-3 social-media-section">
                <div class="top">
                    <p class="m-0"> Connect us on </p>
                </div>

                <div class="d-flex flex-row gap-3 bottom">
                    <a class="pointer">
                        <i class="fa-brands fa-facebook-square fs-3 text-light" id="facebook-icon"></i>
                    </a>

                    <a class="pointer">
                        <i class="fa-brands fa-instagram-square fs-3 text-light" id="instagram-icon"></i>
                    </a>

                    <a class="pointer">
                        <i class="fa-brands fa-twitter-square fs-3 text-light" id="x-icon"></i>
                    </a>
                </div>
            </div>

            <!-- payment options -->
            <div class="d-flex flex-column payment-partner-section align-items-center gap-2">
                <!-- payment partner -->
                <!-- esewa -->
                <div class="d-flex flex-row align-items-center gap-3 payment-partner">
                    <p class="m-0 fs-5"> Our payment partner </p>
                    <div class="border border-1 rounded partner-container">
                        <img src="/bookrack/assets/icons/esewa-logo.webp" alt="" loading="lazy">
                    </div>
                </div>
            </div>
        </div>

        <!-- copyright section -->
        <div class="d-flex flex-column py-3 copyright-section align-items-center">
            <p class="m-0"> © 2024 Bookrack. All rights reserved </p>
        </div>
    </footer>

    <?php require_once __DIR__ . '/../includes/script.php' ?>
</body>

</html>