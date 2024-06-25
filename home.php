<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/");
}

$url = "home";

require_once __DIR__ . '/../bookrack/app/user-class.php';
require_once __DIR__ . '/../bookrack/app/book-class.php';
require_once __DIR__ . '/../bookrack/app/wishlist-class.php';
require_once __DIR__ . '/../bookrack/app/functions.php';

// user object
$profileUser = new User();

// set user id
$profileUser->setUserId($_SESSION['bookrack-user-id']);

// get user details
$userFound = $profileUser->fetch($profileUser->getUserId());

if (!$userFound) {
    header("Location: /bookrack/signout");
}

// book obj 
$bookObj = new Book();

// fetching all books
$bookList = $bookObj->fetchAllBooks();

// fetch user's book
$userBookList = $bookObj->fetchUserBookId($profileUser->getUserId());

// wishlist object
$wishlist = new Wishlist();

$wishlist->setUserId($profileUser->getUserId());

$userWishlist = $wishlist->fetchWishlist();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Home </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/book.css">
    <link rel="stylesheet" href="/bookrack/assets/css/home.css">
</head>

<body>
    <!-- header -->
    <?php
    include 'header.php';
    ?>

    <!-- main -->
    <main class="d-flex flex-row gap-lg-3 pb-5 container main">
        <!-- aside :: filter & advertisement  -->
        <aside class="aside gap-3" id="aside">
            <!-- filter -->
            <section class="filter-section">
                <!-- heading -->
                <section class="d-flex flex-row justify-content-between align-items-center row-heading">
                    <p class="m-0 fw-bold text-secondary"> Filters </p>

                    <div class="d-flex flex-row align-items-center gap-2 icon-container">
                        <i class="fa fa-filter d-none d-lg-block text-secondary"></i>
                        <i class="fa fa-multiply fs-3 d-lg-none text-secondary pointer" id="filter-hide-trigger"></i>
                    </div>
                </section>

                <hr>

                <!-- filter parameters -->
                <section class="mt-3 filter-parameter-section">
                    <form method="GET" class="d-flex flex-column gap-3" id="filter-form">
                        <!-- price -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="min-price" class="form-label"> Price </label>
                                <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <!-- min price -->
                                <input type="number" name="min-price" class="form-control" id="min-price" min="0"
                                    aria-describedby="min price" placeholder="Min">

                                <p class="m-0 fw-bold"> - </p>

                                <!-- max price -->
                                <input type="number" name="max-price" class="form-control" id="max-price" min="0"
                                    aria-describedby="max price" placeholder="Max">
                            </div>
                        </div>

                        <!-- book category type -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="category" class="form-label"> Purpose </label>
                                <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                            </div>

                            <select class="form-select form-select-md" name="purpose" id="purpose"
                                aria-label="Small select example">
                                <option value="all" selected> All </option>
                                <option value="renting"> Renting </option>
                                <option value="buy/sell"> Buy/Sell </option>
                            </select>
                        </div>

                        <!-- book genre -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="genre" class="form-label"> Genre </label>
                                <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                            </div>

                            <select class="form-select form-select-md" name="genre" id="genre"
                                aria-label="Small select example">
                                <option value="all" selected hidden> All genre </option>
                                <?php
                                foreach ($genreArray as $genre) {
                                    ?>
                                    <option value="<?= $genre ?>"> <?= $genre ?> </option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <!-- filter button -->
                        <button type="submit" class="filter-btn" id="filter-btn"> Filter </button>
                    </form>
                </section>
            </section>

            <!-- advertisement -->
            <section class="advertisement-section">
                <img src="/bookrack/assets/images/ad-1.jpg" alt="advertisement" loading="lazy">
            </section>
        </aside>

        <!-- article -->
        <article class="article bg-md-success bg-sm-danger">
            <!-- top genre  -->
            <section class="d-flex flex-row gap-4 flex-wrap mt-2 align-items-center top-genre-section">
                <p class="m-0 fs-5"> Top Genre </p>

                <?php
                $genreList = [];
                foreach ($bookList as $key => $book) {
                    $genreList = $book['genre'];
                }
                ?>

                <!-- fetch all the genres -->
                <div class="d-flex flex-row flex-wrap gap-2 genre-container">
                    <?php
                    foreach ($genreList as $genre) {
                        ?>
                        <div class="genre">
                            <p class="m-0 text-secondary"> <?= $genre ?> </p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>

            <!-- all books section -->
            <section class="d-flex flex-column gap-3 section all-books-section">
                <div class="d-flex justify-content-between align-items-center heading">
                    <p class="m-0 fw-bold heading text-secondary fs-4"> All Books </p>
                    <i class="fa fa-filter text-secondary pointer" id="filter-show-trigger-2"></i>
                </div>

                <!-- all book container -->
                <div class="d-flex flex-row flex-wrap gap-3 all-book-container">
                    <?php
                    foreach ($bookList as $key => $book) {
                        $bookObj->setCoverPhoto($book['photo']['cover']);
                        ?>
                        <div class="book-container">
                            <!-- book image -->
                            <div class="book-image">
                                <!-- <img src="/bookrack/assets/images/cover-1.jpeg" alt=""> -->
                                <img src="<?= $bookObj->getCoverPhotoUrl() ?>" alt="" loading="lazy">
                            </div>

                            <!-- book details -->
                            <div class="book-details">
                                <!-- book title -->
                                <div class="book-title-wishlist">
                                    <p class="book-title">
                                        <?= $book['title'] ?>
                                    </p>

                                    <?php
                                    if (!in_array($key, $userBookList)) {
                                        ?>
                                        <div class="wishlist">
                                            <a href="/bookrack/app/wishlist-code.php?book-id=<?= $key ?>&ref_url=<?= $url ?>">
                                                <?php
                                                if (in_array($key, $userWishlist)) {
                                                    ?>
                                                    <i class="fa-solid fa-bookmark"></i>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <i class="fa-regular fa-bookmark"></i>
                                                    <?php
                                                }
                                                ?>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <!-- book purpose -->
                                <p class="book-purpose"> <?= getPascalCaseString($book['purpose']) ?> </p>

                                <!-- book description -->
                                <div class="book-description-container">
                                    <p class="book-description"> <?= $book['description'] ?> </p>
                                </div>

                                <!-- book price -->
                                <div class="book-price">
                                    <p class="book-price">
                                        <?php
                                        if ($book['purpose'] == "renting") {
                                            $actualPrice = $book['price']['actual'];
                                            $rent = $actualPrice * 0.25;
                                            echo getFormattedPrice($rent) . " /week";
                                        } elseif ($book['purpose'] == "buy/sell") {

                                        }
                                        ?>
                                </div>

                                <button class="btn" onclick="window.location.href='/bookrack/book-details/<?= $key ?>/'">
                                    Show
                                    More </button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <!-- pagination -->
                <div class="d-flex flex-row mt-3 mx-md-auto mx-lg-0 align-items-center pagination-container">
                    <div class="pagination-controller">
                        <i class="fa-solid fa-chevron-left"></i>
                    </div>

                    <div class="pagination-stamp">
                        <p> 1 </p>
                    </div>

                    <div class="pagination-stamp active">
                        <p> 2 </p>
                    </div>

                    <div class="pagination-stamp">
                        <p> 3 </p>
                    </div>

                    <div class="pagination-stamp">
                        <p> 4 </p>
                    </div>

                    <div class="pagination-stamp">
                        <p> 5 </p>
                    </div>

                    <div class="pagination-controller">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </section>
        </article>
    </main>

    <?php include 'footer.php'; ?>

    <!-- jquery -->
    <script src="/bookrack/assets/js/jquery-3.7.1.min.js"></script>

    <!-- bootstrap js :: cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- bootstrap js :: local file -->
    <script src="/bookrack/assets/js/bootstrap-js-5.3.3/bootstrap.min.js"></script>

    <!-- js :: current file -->
    <script>
        // filter
        var filterTriggerState = false;
        const aside = document.getElementById("aside");
        const showFilter = document.getElementById("filter-show-trigger-2");
        const hideFilter = document.getElementById("filter-hide-trigger");

        // filter
        showFilter.addEventListener('click', function () {
            aside.style = "display:block";
            filterTriggerState = !filterTriggerState;
        });

        hideFilter.addEventListener('click', function () {
            aside.style = "display:none";
            filterTriggerState = !filterTriggerState;
        });

        // device width changing
        homeWidthCheck = () => {
            if (window.innerWidth < 1188) {
                // filter
                if (!filterTriggerState) {
                    aside.style.display = "none";
                } else {
                    aside.style.display = "flex";
                }
            }
            if (window.innerWidth >= 1188) {
                showFilter.style.display = "none";
                aside.style.display = "flex";
            } else {
                showFilter.style.display = "block";
            }
        }

        window.addEventListener('resize', homeWidthCheck);
        homeWidthCheck();
    </script>
</body>

</html>