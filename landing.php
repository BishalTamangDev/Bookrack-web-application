<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['bookrack-user-id'])){
    header("Location: /bookrack/home");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Bookrack </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/navbar.css">
    <link rel="stylesheet" href="/bookrack/assets/css/style.css">
    <link rel="stylesheet" href="/bookrack/assets/css/header.css">
    <link rel="stylesheet" href="/bookrack/assets/css/footer.css">
    <link rel="stylesheet" href="/bookrack/assets/css/landing.css">
    <link rel="stylesheet" href="/bookrack/assets/css/book.css">

</head>

<body>
    <!-- header -->
    <header class="header w-100 border-bottom">
        <div class="container d-flex flex-row p-3 align-items-center justify-content-between header-container">
            <!-- logo -->
            <div class="d-flex flex-row gap-2 align-items-center header-logo pointer"
                onclick="window.location.href='/bookrack/landing'">
                <img src="/bookrack/assets/brand/bookrack-logo-black.png" alt="">
                <h3 class="f-reset fw-bold"> Bookrack </h3>
            </div>

            <!-- menu bar -->
            <i class="fa fa-bars pointer fs-3 d-md-none" id="open-menu"></i>

            <div class="d-flex flex-column flex-md-row gap-3 p-4 p-md-0 align-items-center search-signin" id="menu">
                <!-- close menu -->
                <div class="d-flex d-md-none justify-content-end container p-3 close" id="close-menu">
                    <i class="fa fa-multiply pointer fs-3"></i>
                </div>

                <!-- search -->
                <form class="search-form">
                    <input type="search" name="search-content" id="search" placeholder="search here" class="p-2">
                </form>

                <!-- signin -->
                <a href="/bookrack/signin" class="btn rounded px-md-3 px-5 py-2 signin-btn"> Signin </a>
            </div>
        </div>
    </header>

    <!-- main -->
    <main class="main">
        <!-- landing section -->
        <section class="section container d-flex flex-row align-items-center landing-section">
            <div class="d-flex flex-column m-auto landing-detail">
                <p class="f-reset title"> Share Your Favourite Reads with <br> The World </p>
                <p class="f-reset detail">
                    Discover a community where book lovers unite! Bookrack is your go-to platform for sharing and
                    discovering books that ignite your imagination, inspire your dreams, and expand your horizons.
                    Whether you're a passionate reader, an aspiring writer, or simply looking for your next great read,
                    Bookrack is here to connect you with a world of literary wonders.
                </p>
                <a href="/bookrack/signup" class="btn btn-warning text-light"> JOIN NOW </a>
            </div>
        </section>

        <!-- trending books -->
        <section class="section container d-flex flex-column gap-5 trending-book-section">
            <p class="f-reset fw-bold fs-1 title title"> Trending Books </p>

            <div class="d-flex flex-row flex-wrap gap-3 trending-book-container">
                <!-- book container :: dummy data 1 -->
                <div class="book-container">
                    <!-- book image -->
                    <div class="book-image">
                        <img src="/bookrack/assets/images/cover-1.jpeg" alt="">
                    </div>

                    <!-- book details -->
                    <div class="book-details">
                        <!-- book title -->
                        <div class="book-title">
                            <p class="book-title"> To Kill a Mockingbird </p>
                            <i class="fa-regular fa-bookmark"></i>
                        </div>

                        <!-- book purpose -->
                        <p class="book-purpose"> Renting </p>

                        <!-- book description -->
                        <div class="book-description-container">
                            <p class="book-description"> Set in the American South during the 1930s, this classic
                                novel explores themes of racial injustice and moral growth through the eyes of Scout
                                Finch, a young girl whose father, Atticus Finch, is ... </p>
                        </div>

                        <!-- book price -->
                        <div class="book-price">
                            <p class="book-price"> NRs. 85 </p>
                        </div>

                        <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More </button>
                    </div>
                </div>

                <!-- book container :: dummy data 2 -->
                <div class="book-container">
                    <!-- book image -->
                    <div class="book-image">
                        <img src="/bookrack/assets/images/cover-2.png" alt="">
                    </div>

                    <!-- book details -->
                    <div class="book-details">
                        <!-- book title -->
                        <div class="book-title">
                            <p class="book-title"> Don't Look Back </p>
                            <i class="fa-regular fa-bookmark"></i>
                        </div>

                        <!-- book purpose -->
                        <p class="book-purpose"> Selling </p>

                        <!-- book description -->
                        <div class="book-description-container">
                            <p class="book-description"> Set in the American South during the 1930s, this classic
                                novel explores themes of racial injustice and moral growth through the eyes of Scout
                                Finch, a young girl whose father, Atticus Finch, is ... </p>
                        </div>

                        <!-- book price -->
                        <div class="book-price">
                            <p class="book-price"> NRs. 170 </p>
                        </div>

                        <button class="btn" onclick="window.location.href='/bookrack/book-details'"> Show More </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- application features section -->
        <section class="section container d-flex flex-column gap-5 feature-section">
            <!-- title -->
            <p class="f-reset fw-bold fs-1 title title"> What do we offer? </p>

            <!-- feature cards -->
            <div class="d-flex flex-row gap-5 justify-content-between feature-card-container">
                <div class="card">
                    <div class="feature-image">
                        <img class="card-img-top" src="/bookrack/assets/images/rent.jpg" alt="rent out book"
                            loading="lazy">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"> RENT OUT YOUR FAVOURITE BOOKS </h4>
                        <p class="card-text"> From an ocean of books, you can choose the ones you like and rent them for
                            a period of time. </p>
                    </div>
                </div>

                <div class="card">
                    <div class="feature-image">
                        <img class="card-img-top" src="/bookrack/assets/images/buy-and-sell.jpg" alt="buy & sell book"
                            loading="lazy">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"> BUY & SELL USED BOOKS </h4>
                        <p class="card-text"> Wanna keep the books forever with you? Obviously you can keep it from us.
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="feature-image">
                        <img class="card-img-top" src="/bookrack/assets/images/earn.jpg"
                            alt="earn by placing book on rent" loading="lazy">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"> EARN FROM YOUR OLD BOOKS </h4>
                        <p class="card-text"> For each circulation of your books on rent, you receive the share of it.
                            <span class="text-warning">JOIN US NOW TO EARN.</span>
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="feature-image">
                        <img class="card-img-top" src="/bookrack/assets/images/dropshipping.jpg" alt="dropshipping"
                            loading="lazy">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"> DROPSHIPPING </h4>
                        <p class="card-text"> We pick your books and aslo drop your orders everytime, on time. </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- genre section -->
        <section class="section container d-flex flex-column gap-4 genre-section">
            <p class="f-reset fw-bold fs-1 title title"> Genres </p>
            <p class="f-reset fs-5"> We provide books of wide range of genre. Feel free to explore those books and rent
                them instantly. </p>

            <div class="d-flex flex-row flex-wrap container gap-3 genre-container">
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Adventure</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Art & Photography</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Biography</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Chick Lit</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Children’s</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Classics</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Contemporary</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Cookbooks</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Dystopian</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Drama</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Essays</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Fairy Tales & Folklore</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Fantasy</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Graphic Novels</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Guide / How-to</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Health & Fitness</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Historical Fiction</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">History</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Horror</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Humor</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Humor & Satire</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Journalism</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">LGBTQ+</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Literary Fiction</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Magical Realism</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Memoir & Autobiography</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Motivational</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Mystery</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">New Adult (NA)</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Parenting & Families</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Paranormal Romance</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Philosophy</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Poetry</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Politics</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Religion & Spirituality</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Romance</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Science</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Science Fiction</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Self-Help</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Short Stories</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Thriller</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Travel</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">True Crime</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Western</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Women’s Fiction</p>
                </div>
                <div class="d-flex flex-row genre-card">
                    <p class="genre-title">Young Adult (YA)</p>
                </div>
            </div>

        </section>
    </main>

    <!-- footer -->
    <footer class="d-flex flex-column gap-4 section footer">
        <div class="d-flex flex-column container footer-container">
            <!-- main footer -->
            <div class="d-flex flex-row justify-content-lg-between flex-wrap main-footer">
                <!-- physical location -->
                <div class="d-flex flex-column physical-location-section">
                    <!-- title -->
                    <p class="f-reset fw-bold footer-title"> Our Physical Location </p>
                    <!-- content -->
                    <div class="d-flex flex-row gap-3 content">
                        <div class="left">
                            <i class="fa fa-map-pin"></i>
                        </div>

                        <div class="d-flex flex-column right">
                            <p class="f-reset shop-name"> Bookrack Book Shop </p>
                            <p class="f-reset shop-location"> Bansbari, Kathmandu </p>
                        </div>
                    </div>
                </div>

                <!-- quick links -->
                <div class="main-footer-content-section">
                    <!-- title -->
                    <p class="f-reset fw-bold footer-title"> Quick Links </p>

                    <!-- content -->
                    <div class="d-flex flex-row content">
                        <ul list-style-type="none">
                            <li><a href=""> Book Offer </a></li>
                            <li><a href=""> Book request </a></li>
                            <li><a href=""> New Arrival </a></li>
                            <li><a href=""> Blogs </a></li>
                            <li><a href=""> Shipping Rates </a></li>
                        </ul>
                    </div>
                </div>

                <!-- about -->
                <div class="main-footer-content-section">
                    <!-- title -->
                    <p class="f-reset fw-bold footer-title"> About Us </p>
                    <!-- content -->
                    <div class="d-flex flex-row gap-2 content">
                        <ul>
                            <li><a href=""> Company </a></li>
                            <li><a href=""> Features </a></li>
                            <li><a href=""> Objectoves </a></li>
                            <li><a href=""> Contact </a></li>
                        </ul>
                    </div>
                </div>

                <!-- popular genres -->
                <div class="main-footer-content-section">
                    <!-- title -->
                    <p class="f-reset fw-bold footer-title"> Popular Genres </p>
                    <!-- content -->
                    <div class="d-flex flex-row gap-2 content">
                        <ul>
                            <li><a href=""> History </a></li>
                            <li><a href=""> Art </a></li>
                            <li><a href=""> Sci-Fi </a></li>
                            <li><a href=""> Nightmare </a></li>
                            <li><a href=""> Something </a></li>
                        </ul>
                    </div>
                </div>

                <!-- mobile application -->
                <div class="d-flex flex-column mobile-app-section gap-2">
                    <!-- title -->
                    <p class="f-reset fw-bold footer-title"> Get Bookrack in <br> Your Pocket </p>
                    <!-- content -->
                    <div class="d-flex flex-column gap-2 content-container">
                        <!-- android -->
                        <div class="content">
                            <div class="left">
                                <i class="fa-brands fa-google-play"></i>
                            </div>
                            <div class="right">
                                <p class="f-reset"> GET IT ON </p>
                                <p class="f-reset"> Google Play </p>
                            </div>
                        </div>

                        <!-- ios -->
                        <div class="content">
                            <div class="left">
                                <i class="fa-brands fa-apple text-light"></i>
                            </div>
                            <div class="right">
                                <p class="f-reset"> GET IT ON </p>
                                <p class="f-reset"> App Store </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- social media connection -->
            <div class="d-flex flex-column social-media-section align-items-center gap-3">
                <div class="top">
                    <p class="f-reset"> Connect us on </p>
                </div>

                <div class="d-flex flex-row gap-2 bottom">
                    <a href="">
                        <i class="fa-brands fa-facebook-square fs-3 text-dark" id="facebook-icon"></i>
                    </a>

                    <a href="">
                        <i class="fa-brands fa-instagram-square fs-3 text-dark" id="instagram-icon"></i>
                    </a>

                    <a href="">
                        <i class="fa-brands fa-twitter-square fs-3 text-dark" id="x-icon"></i>
                    </a>
                </div>
            </div>

            <!-- payment options -->
            <div class="d-flex flex-column payment-partner-section align-items-center gap-2">
                <!-- payment partner -->
                <!-- esewa -->
                <div class="d-flex flex-row payment-partner align-items-center gap-3">
                    <p class="f-reset fs-5"> Our payment partner </p>
                    <div class="partner-container px-2 border border-1 rounded">
                        <img src="assets/icons/esewa-logo.webp" alt="">
                    </div>
                </div>
            </div>

            <!-- copyright section -->
            <div class="d-flex flex-column copyright-section align-items-center mb-4">
                <p class="f-reset"> © Copyright protected by Bookrack, 2024️ </p>
            </div>
        </div>
    </footer>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script>
        var menuState = false;
        const menu = document.getElementById("menu");
        const openMenu = document.getElementById("open-menu");
        const closeMenu = document.getElementById("close-menu");

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
                    menu.style = "right: -100%; transition: 0s";
                } else {
                    menu.style = "right: 0; transition: 0.4s";
                }
            }
        }

        window.addEventListener('resize', widthCheck);

        window.onload = () => {
            widthCheck();
        }
    </script>
</body>

</html>