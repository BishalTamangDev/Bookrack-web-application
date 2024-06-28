<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");
elseif (isset($_SESSION['bookrack-admin-id']))
    header("Location: /bookrack/admin/admin-dashboard");

$url = "landing";

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

    <link rel="stylesheet" href="/bookrack/assets/css/landing.css">
    <link rel="stylesheet" href="/bookrack/assets/css/book.css">
</head>

<body>
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
                    Discover a community where book lovers unite! Bookrack is your go-to platform for sharing and
                    discovering books that ignite your imagination, inspire your dreams, and expand your horizons.
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
                if (sizeof($bookList) > 0) {
                    foreach ($bookList as $book) {
                        ?>
                        <div class="book-container">
                            <!-- book image -->
                            <div class="book-image">
                                <img src="<?= $book->photoUrl['cover'] ?>" alt="">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title-wishlist">
                                    <p class="book-title"> <?= ucwords($book->title) ?> </p>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> <?= ucwords($book->purpose) ?> </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> <?= ucfirst($book->description) ?> </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price">
                                        <?php
                                        if ($book->purpose == 'renting') {
                                            $rent = 0.20 * $book->price['actual'];
                                            echo "NPR." . number_format($rent, 2) . "/week";
                                        } else {
                                            echo "NPR." . number_format($book->price['offer'], 2);
                                        }
                                        ?>
                                    </p>
                                </div>

                                <button class="btn"
                                    onclick="window.location.href='/bookrack/book-details/<?= $book->getId() ?>'"> Show More
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                } else {
                    ?>
                    <p class="m-0 text-danger"> Book hasn't been added yet! </p>
                    <?php
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
                foreach ($genreArray as $genre) {
                    ?>
                    <div class="d-flex flex-row genre-card">
                        <p class="genre-title"> <?= $genre ?> </p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
    </main>

    <!-- footer -->
    <?php include_once 'footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/app/script-include.php'; ?>
</body>

</html>