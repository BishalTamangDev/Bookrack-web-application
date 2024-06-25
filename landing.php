<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['bookrack-user-id'])){
    header("Location: /bookrack/home");
}elseif(isset($_SESSION['bookrack-admin-id'])){
    header("Location: /bookrack/admin/admin-dashboard");
}

require_once __DIR__ . '/../bookrack/app/functions.php';
require_once __DIR__ . '/../bookrack/app/book-class.php';


$bookObj = new Book();

// fetch all books
$bookList = $bookObj->fetchAllBooks();
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
    <link rel="stylesheet" href="/bookrack/assets/css/landing.css">
    <link rel="stylesheet" href="/bookrack/assets/css/book.css">

</head>

<body>
    <!-- header -->
    <?php include 'header-unsigned.php'; ?>

    <!-- main -->
    <main class="main">
        <!-- landing section -->
        <section class="container d-flex flex-column-reverse gap-5 flex-md-row mt-5 landing-container">
            <div class="d-flex flex-column w-100 w-md-75 mt-5 detail">
                    <p class="m-0 fw-bold heading">
                        Share Your Favorite Reads With The World
                    </p>

                    <p class="m-0 fs-4 mt-5 text-secondary description">
                        Discover a community where book lovers unite! Bookrack is your go-to platform for sharing and discovering books that ignite your imagination, inspire your dreams, and expand your horizons. 
                    </p>

                    <a href="/bookrack/signup" class="btn join-btn mt-4 text-white px-4 py-2"> JOIN NOW </a>
            </div>

            <!-- landing image -->
             <div class="w-100 w-md-25 image-container">
                <img src="/bookrack/assets/images/reading.svg" alt="">
             </div>
        </section>

        <!-- trending books -->
        <section class="section container d-flex flex-column gap-5 trending-book-section" id="trending-book-section">
            <p class="f-reset fw-bold fs-1 title title"> Trending Books </p>

            <div class="d-flex flex-row flex-wrap gap-3 trending-book-container">
                <?php
                if(sizeof($bookList) > 0){
                    foreach($bookList as $key => $book){
                        $bookObj->setCoverPhoto($book['photo']['cover']);
                        ?>
                        <div class="book-container">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="<?=$bookObj->getCoverPhotoUrl()?>" alt="">
                            </div>
    
                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title-wishlist">
                                    <p class="book-title"> <?=$book['title']?> </p>
                                </div>
    
                                <!-- book purpose -->
                                <p class="book-purpose"> <?=getPascalCaseString($book['purpose'])?> </p>
    
                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> <?=$book['description']?> </p>
                                </div>
    
                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price"> <?=getFormattedPrice($book['price']['offer'])?> </p>
                                </div>
    
                                <button class="btn" onclick="window.location.href='/bookrack/book-details/<?= $key ?>/'"> Show More </button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                }else{

                }
                ?>
            </div>
        </section>

        <!-- application features section -->
        <section class="section container d-flex flex-column gap-5 feature-section" id="service-section">
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
        <section class="section container d-flex flex-column gap-4 genre-section" id="genre-section">
            <p class="f-reset fw-bold fs-1 title title"> Genres </p>
            <p class="f-reset fs-5"> We provide books of wide range of genre. Feel free to explore those books and rent
                them instantly. </p>

            <div class="d-flex flex-row flex-wrap container gap-2 genre-container">
                <?php 
                foreach($genreArray as $genre){
                    ?>
                    <div class="d-flex flex-row genre-card">
                        <p class="genre-title"> <?=$genre?> </p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
    </main>

    <!-- footer -->
    <?php include_once 'footer.php';?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/../bookrack/app/jquery-js-bootstrap-include.php';?>
</body>

</html>