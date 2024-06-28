<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

if (!isset($bookId) || $bookId == "")
    header("Location: /bookrack/home");

$url = "book-details";
$userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/user-class.php';

$profileUser = new User();
$userExists = $profileUser->fetch($userId);

if (!$userExists)
    header("Location: /bookrack/signin");

require_once __DIR__ . '/app/book-class.php';
$selectedBook = new Book();
$bookExists = $selectedBook->fetch($bookId);

require_once __DIR__ . '/app/wishlist-class.php';
$wishlist = new Wishlist();
$wishlist->setUserId($userId);
$userWishlist = $wishlist->fetchWishlist();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> <?= $bookExists ? ucWords($selectedBook->title) : "Book details" ?> </title>

    <?php require_once __DIR__ . '/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/book-details.css">
</head>

<body>
    <!-- header -->
    <?php include 'header.php'; ?>

    <!-- main -->
    <?php
    if ($bookExists) {
        ?>
        <main class="d-flex flex-column gap-3 pb-5 container main">
            <!-- title, rating, count -->
            <section class="d-flex flex-column title-rating-count-container">
                <!-- book title -->
                <p class="f-reset fw-bold fs-3"> <?= ucWords($selectedBook->title) ?> </p>

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
                                <img src="<?= $selectedBook->photoUrl['cover'] ?>" alt="book cover photo" loading="lazy">
                            </abbr>
                        </div>

                        <!-- price page -->
                        <div class="book-image">
                            <abbr title="price page">
                                <img src="<?= $selectedBook->photoUrl['price'] ?>" alt="book price photo" loading="lazy">
                            </abbr>
                        </div>

                        <!-- ISBN page -->
                        <div class="book-image">
                            <abbr title="isbn page">
                                <img src="<?= $selectedBook->photoUrl['isbn'] ?>" alt="book isbn photo" loading="lazy">
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
                            foreach ($selectedBook->author as $author) {
                                ?>
                                <div class="author">
                                    <p class="m-0"> <?= ucWords($author) ?> </p>
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
                                <p class="m-0"> <?= ucWords($selectedBook->publisher) ?> </p>
                            </div>
                        </div>

                        <!-- Publication -->
                        <div class="misc-div">
                            <div class="title">
                                <p class="m-0 fw-bold"> Publication </p>
                            </div>
                            <div class="data">
                                <p class="m-0"> <?= ucWords($selectedBook->publication) ?> </p>
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
                    <?php
                    if ($selectedBook->getOwnerId() != $userId) {
                        ?>
                        <div class="d-flex flex-wrap flex-md-row operation-container">
                            <!-- request button -->
                            <!-- <a href="" class="btn" id="request-btn"> REQUEST NOW </a> -->

                            <!-- wishlist -->
                            <a href="/bookrack/app/wishlist-code.php?book-id=<?= $bookId ?>&ref_url=<?= $url ?>" class="btn"
                                id="wishlist-btn">
                                <?php
                                if (in_array($bookId, $userWishlist)) {
                                    ?>
                                    <i class="fa-solid fa-bookmark"></i> Remove from wishlist
                                    <?php
                                } else {
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
        <?php
    } else {
        ?>
        <main class="d-flex flex-column gap-3 pb-5 container main">
            <!-- title, rating, count -->
            <section class="d-flex flex-column title-rating-count-container">
                <h1> Book Not Found! </h1>
            </section>
        </main>
        <?php
    }
    ?>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/app/script-include.php'; ?>
</body>

</html>