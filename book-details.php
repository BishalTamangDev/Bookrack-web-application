<?php

// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/home");
}

if (!isset($bookId) || $bookId == "") {
    header("Location: /bookrack/home");
}

$url = "book-details";

// fetch book details
require_once __DIR__ . '/../bookrack/app/user-class.php';
require_once __DIR__ . '/../bookrack/app/book-class.php';
require_once __DIR__ . '/../bookrack/app/wishlist-class.php';
require_once __DIR__ . '/../bookrack/app/functions.php';

$profileUser = new User();
$profileUser->setUserId($_SESSION['bookrack-user-id']);
$profileUser->fetch($_SESSION['bookrack-user-id']);

$selectedBook = new Book();

$selectedBook->setId($bookId);

$bookFound = $selectedBook->fetch($selectedBook->getId());

if (!$bookFound) {
    header("Location: /bookrack/home");
}

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
    <title> <?= $selectedBook->getTitle() ?> </title>

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
    <link rel="stylesheet" href="/bookrack/assets/css/book-details.css">
</head>

<body>
    <!-- header -->
    <?php
    include 'header.php';
    ?>

    <!-- main -->
    <main class="d-flex flex-column gap-3 pb-5 container main">
        <!-- title, rating, count -->
        <section class="d-flex flex-column title-rating-count-container">
            <!-- book title -->
            <p class="f-reset fw-bold fs-3"> <?= $selectedBook->getTitle() ?> </p>

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
                        <img src="<?=$selectedBook->getCoverPhotoUrl()?>" alt="" loading="lazy">
                        <!-- <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy"> -->
                    </div>
                </div>

                <!-- bottom images -->
                <div class="d-flex flex-row flex-md-column flex-lg-row gap-2 bottom">
                    <!-- cover -->
                    <div class="book-image">
                        <abbr title="cover page">
                            <img src="<?=$selectedBook->getCoverPhotoUrl()?>" alt="" loading="lazy">
                            <!-- <img src="/bookrack/assets/images/cover-1.jpeg" alt="" loading="lazy"> -->
                        </abbr>
                    </div>

                    <!-- price page -->
                    <div class="book-image">
                        <abbr title="price page">
                            <img src="<?=$selectedBook->getPricePhotoUrl()?>" alt="" loading="lazy">
                            <!-- <img src="/bookrack/assets/images/book-3.jpg" alt="" loading="lazy"> -->
                        </abbr>
                    </div>

                    <!-- ISBN page -->
                    <div class="book-image">
                        <abbr title="isbn page">
                            <img src="<?=$selectedBook->getIsbnPhotoUrl()?>" alt="" loading="lazy">
                            <!-- <img src="/bookrack/assets/images/ISBN-1.jpg" alt="" loading="lazy"> -->
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
                            $purpose = $selectedBook->getPurpose(); 
                            if( $purpose == "renting"){
                                ?>
                                <p class="f-reset text-light"> Available for Rent </p>
                                <?php
                            }elseif($purpose == "buy/sell"){
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
                        <p class="f-reset"> <?= $selectedBook->getDescription() ?> </p>
                    </div>
                </div>

                <!-- genre container -->
                <div class="d-flex flex-column gap-2 genre-container">
                    <!-- genre heading -->
                    <p class="p f-reset fw-bold fs-4 text-secondary"> Genre </p>

                    <!-- genre list -->
                    <div class="d-flex flex-row gap-2 align-items-cente flex-wrap genre-list">
                        <?php
                        $genreArray = $selectedBook->getGenre();

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
                        $authorArray = $selectedBook->getAuthor();

                        foreach ($authorArray as $author) {
                            ?>
                            <div class="author">
                                <p class="m-0"> <?= $author ?> </p>
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
                            <p class="m-0"><?= $selectedBook->getEdition() ?><sup><?php
                            $remainder = $selectedBook->getEdition() % 10;

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
                            <p class="m-0"> <?= $selectedBook->getPublisher() ?> </p>
                        </div>
                    </div>

                    <!-- Publication -->
                    <div class="misc-div">
                        <div class="title">
                            <p class="m-0 fw-bold"> Publication </p>
                        </div>
                        <div class="data">
                            <p class="m-0"> <?= $selectedBook->getPublication() ?> </p>
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
                                if($selectedBook->getPurpose() == "renting"){
                                    $actualPrice = $selectedBook->getActualPrice();
                                    $rent = $actualPrice * 0.25;
                                    echo getFormattedPrice($rent)." /week";
                                }elseif($selectedBook->getPurpose() == "buy/sell") {

                                }
                                getFormattedPrice($selectedBook->getOfferPrice());
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
                            <p class="m-0"> <?= $selectedBook->getIsbn() ?> </p>
                        </div>
                    </div>

                    <!-- language -->
                    <div class="misc-div">
                        <div class="title">
                            <p class="m-0 fw-bold"> Language </p>
                        </div>
                        <div class="data">
                            <p class="m-0"> <?= getPascalCaseString($selectedBook->getLanguage()) ?> </p>
                        </div>
                    </div>
                </div>

                <!-- action -->
                <?php
                if ($selectedBook->getOwnerId() != $_SESSION['bookrack-user-id']) {
                    ?>
                    <div class="d-flex flex-wrap flex-md-row operation-container">
                        <!-- request button -->
                        <!-- <a href="" class="btn" id="request-btn"> REQUEST NOW </a> -->

                        <!-- wishlist -->
                        <a href="/bookrack/app/wishlist-code.php?book-id=<?= $bookId ?>&ref_url=<?= $url ?>" class="btn" id="wishlist-btn">
                            <?php
                            if(in_array($selectedBook->getId(),$userWishlist)){
                                ?>
                                <i class="fa-solid fa-bookmark"></i> Remove from wishlist
                                <?php
                            }else{
                                ?>
                                <i class="fa-regular fa-bookmark"></i> Add to wishlist
                                <?php
                            }
                            ?>
                        </a>

                        <!-- add to cart -->
                        <a href="" class="btn" id="cart-btn"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
    </main>

    <!-- footer -->
    <?php include 'footer.php';?>

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

    </script>
</body>

</html>