<?php

if (!isset($bookId) || $bookId == "")
    header("Location: /bookrack/home");

// starting the session
if (session_status() == PHP_SESSION_NONE)
    session_start();

$url = "book-details-unsigned";

// fetch book details
require_once __DIR__ . '/app/book-class.php';
require_once __DIR__ . '/app/functions.php';

$selectedBook = new Book();

$bookFound = $selectedBook->fetch($bookId);

if (!$bookFound) {
    header("Location: /bookrack/home");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> <?= $bookFound ? ucwords($selectedBook->title) : "Book not found"; ?> </title>

    <?php require_once __DIR__ . '/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/book-details.css">

    <style>
        .main{
            margin-top: 8rem;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include 'header-unsigned.php'; ?>

    <!-- main -->
    <?php
    if ($bookFound) {
        ?>
        <main class="d-flex flex-column gap-3 pb-5 container main">
            <!-- title, rating, count -->
            <section class="d-flex flex-column title-rating-count-container">
                <!-- book title -->
                <p class="f-reset fw-bold fs-3"> <?= ucwords($selectedBook->title) ?> </p>

                <!-- rating & count-->
                <div class="d-flex flex-row gap-2 align-items-center rating-count-container">
                    <!-- rating -->
                    <div class="d-flex flex-row align-items-center rating-container">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/full-rating.png" alt="" loading="lazy">
                        <img src="/bookrack/assets/icons/half-rating.png" alt="" loading="lazy">
                    </div>

                    <!-- count -->
                    <p class="f-reset count-container"> (<?= "-" ?>) </p>
                </div>
            </section>

            <section class="d-flex flex-column flex-lg-row gap-4 book-detail-container">
                <!-- images -->
                <div class="d-flex flex-column flex-md-row flex-lg-column gap-2 book-image-container">
                    <!-- top image -->
                    <div class="d-flex flex-row top">
                        <div class="book-image">
                            <img src="<?= $selectedBook->photoUrl['cover'] ?>" alt="" loading="lazy">
                        </div>
                    </div>

                    <!-- bottom images -->
                    <div class="d-flex flex-row flex-md-column flex-lg-row gap-2 bottom">
                        <!-- cover -->
                        <div class="book-image">
                            <abbr title="cover page">
                                <img src="<?= $selectedBook->photoUrl['cover'] ?>" alt="" loading="lazy">
                            </abbr>
                        </div>

                        <!-- price page -->
                        <div class="book-image">
                            <abbr title="price page">
                                <img src="<?= $selectedBook->photoUrl['price'] ?>" alt="" loading="lazy">
                            </abbr>
                        </div>

                        <!-- ISBN page -->
                        <div class="book-image">
                            <abbr title="isbn page">
                                <img src="<?= $selectedBook->photoUrl['isbn'] ?>" alt="" loading="lazy">
                            </abbr>
                        </div>
                    </div>
                </div>

                <!-- details -->
                <div class="d-flex flex-column gap-4 mt-2 mt-lg-0 all-detail-container">
                    <!-- description & availability status -->
                    <div class="d-flex flex-column justify-content-between gap-2 description-availability">
                        <!-- description heading & availability -->
                        <div class="d-flex flex-row justify-content-between description-deading-availability">
                            <p class="p f-reset fw-bold fs-4 text-secondary"> Description </p>

                            <div class="d-flex flex-row align-items-center bg-success px-3 availability-div">
                                <?php
                                $purpose = $selectedBook->purpose;
                                if ($purpose == "renting") {
                                    ?>
                                    <p class="f-reset text-light"> Available for Rent </p>
                                    <?php
                                } elseif ($purpose == "buy/sell") {
                                    ?>
                                    <p class="f-reset text-light"> Available for Buy/Sell </p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                        <!-- description container -->
                        <div class="description-container">
                            <!-- description -->
                            <p class="f-reset"> <?= ucfirst($selectedBook->description) ?> </p>
                        </div>
                    </div>

                    <!-- genre container -->
                    <div class="d-flex flex-column gap-2 genre-container">
                        <!-- genre heading -->
                        <p class="p f-reset fw-bold fs-4 text-secondary"> Genre </p>

                        <!-- genre list -->
                        <div class="d-flex flex-row gap-2 align-items-cente flex-wrap genre-list">
                            <?php
                            $genreArray = $selectedBook->genre;

                            foreach ($genreArray as $genre) {
                                ?>
                                <div class="bg-dark genre">
                                    <p class="m-0 text-white"> <?= $genre ?> </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- author container -->
                    <div class="d-flex flex-column gap-2 author-container">
                        <!-- authors heading -->
                        <p class="p f-reset fw-bold fs-4 text-secondary"> Author[s] </p>

                        <!-- author list -->
                        <div class="d-flex flex-row gap-2 align-items-center flex-wrap author-list">
                            <?php
                            $authorArray = $selectedBook->author;

                            foreach ($authorArray as $author) {
                                ?>
                                <div class="author">
                                    <p class="m-0"> <?= ucwords($author) ?> </p>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- remaining details -->
                    <div class="d-flex flex-column flex-md-row flex-wrap misc-container">
                        <!-- edition -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Edition </p>
                            </div>
                            <div class="data">
                                <p class="m-0"><?= $selectedBook->edition ?><sup><?php
                                  $remainder = $selectedBook->edition % 10;

                                  switch ($remainder) {
                                      case 1;
                                          echo "st";
                                          break;
                                      case 2;
                                          echo "nd";
                                          break;
                                      case 3:
                                          echo "rd";
                                          break;
                                      default:
                                          echo "th";
                                  }
                                  ?></sup></p>
                            </div>
                        </div>

                        <!-- publisher -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Publisher </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= ucwords($selectedBook->publisher) ?> </p>
                            </div>
                        </div>

                        <!-- Publication -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Publication </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= ucwords($selectedBook->publication) ?> </p>
                            </div>
                        </div>

                        <!-- price -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Price </p>
                            </div>
                            <div class="data">
                                <p class="m-0 text-success fw-bold">
                                    <?php
                                    if ($selectedBook->purpose == "renting") {
                                        $rent = 0.20 * $selectedBook->price['actual'];
                                        echo "NPR." . number_format($rent, 2) . "/week";
                                    } else {
                                        $price = $selectedBook->price['offer'];
                                        echo "NPR." . number_format($price, 2);
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <!-- ISBN -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> ISBN </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= $selectedBook->isbn ?> </p>
                            </div>
                        </div>

                        <!-- language -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Language </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= ucfirst($selectedBook->language) ?> </p>
                            </div>
                        </div>
                    </div>

                    <!-- action -->
                    <div class="d-flex flex-wrap flex-md-row operation-container">
                        <!-- request button -->
                        <!-- <a href="" class="btn" id="request-btn"> REQUEST NOW </a> -->

                        <!-- wishlist -->
                        <a class="btn" id="wishlist-btn" data-bs-toggle="modal" data-bs-target="#joinModal">
                            <i class="fa-regular fa-bookmark"></i> Add to wishlist
                        </a>

                        <!-- add to cart -->
                        <a class="btn" id="cart-btn" data-bs-toggle="modal" data-bs-target="#joinModal">
                            <i class="fa fa-shopping-cart"> </i> Add to cart
                        </a>
                    </div>
            </section>
        </main>
        <?php
    } else {
        ?>
        <main class="d-flex flex-column gap-3 pb-5 container main">
            <section class="d-flex flex-column gap-3 not-found-section">
                <h3 class="m-0"> Book not found! </h3>
            </section>
        </main>
        <?php
    }
    ?>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <!-- modal -->
    <div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="joinModalLabel"> NOT SIGNED IN!!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="m-0"> You need to sign in first to use this feature. </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
                    <a href="/bookrack/signin" class="btn btn-warning text-white"> Sign in now </a>
                </div>
            </div>
        </div>
    </div>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/app/script-include.php'; ?>
</body>

</html>