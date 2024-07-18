<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/");


$userId = $_SESSION['bookrack-user-id'];
$url = "home";

require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/user-class.php';
require_once __DIR__ . '/app/genre-class.php';

// get user details
$profileUser = new User();
$userExists = $profileUser->checkUserExistenceById($userId);

if (!$userExists)
    header("Location: /bookrack/signout");

$profileUser->setUserId($userId);

// book obj 
require_once __DIR__ . '/app/book-class.php';
$bookObj = new Book();
$bookIdList = $bookObj->fetchAllBookId();

// wishlist object
require_once __DIR__ . '/app/wishlist-class.php';
$wishlist = new Wishlist();
$wishlist->setUserId($userId);

// filter
$filterState = (isset($_GET['min-price']) && isset($_GET['max-price']) && isset($_GET['purpose']) && isset($_GET['genre'])) ? true : false;

if ($filterState) {
    $minPrice = $_GET['min-price'] != '' ? $_GET['min-price'] : 0;
    $maxPrice = $_GET['max-price'] != '' ? $_GET['max-price'] : 0;
    $filterPurpose = $_GET['purpose'];
    $filterGenre = $_GET['genre'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- title -->
    <title> Home </title>

    <?php require_once __DIR__ . '/app/header-include.php' ?>

    <!-- css files -->
    <link rel="stylesheet" href="/bookrack/assets/css/book.css">
    <link rel="stylesheet" href="/bookrack/assets/css/home.css">
</head>

<body>
    <!-- header -->
    <?php include 'header.php'; ?>

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
                        <i class="fa fa-filter text-secondary filter-icon"></i>
                        <i class="fa fa-multiply fs-3 text-secondary pointer" id="filter-hide-trigger"></i>
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

                                <abbr title="Reset">
                                    <a href="/bookrack/home">
                                        <i class="fa-solid fa-rotate-left text-secondary pointer"></i>
                                    </a>
                                </abbr>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <!-- min price -->
                                <input type="number" name="min-price" class="form-control" id="min-price" min="0" value="<?php if ($filterState) {
                                    if ($minPrice != 0) {
                                        echo $minPrice;
                                    }
                                }
                                ?>" aria-describedby="min price" placeholder="Min">

                                <p class="m-0 fw-bold"> - </p>

                                <!-- max price -->
                                <input type="number" name="max-price" class="form-control" id="max-price" min="0" value="<?php if ($filterState) {
                                    if ($maxPrice != 0) {
                                        echo $maxPrice;
                                    }
                                }
                                ?>" aria-describedby="max price" placeholder="Max">
                            </div>
                        </div>

                        <!-- book purpoe -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="category" class="form-label"> Purpose </label>
                            </div>

                            <select class="form-select form-select-md" name="purpose" id="purpose"
                                aria-label="Small select example">
                                <?php
                                if ($filterState) {
                                    if ($filterPurpose != 'all') {
                                        ?>
                                        <option value="<?= $filterPurpose ?>" selected hidden> <?= ucfirst($filterPurpose) ?>
                                        </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="all" selected> All </option>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <option value="all" selected hidden> All </option>
                                    <?php
                                }
                                ?>
                                <option value="all"> All </option>
                                <option value="renting"> Renting </option>
                                <option value="buy/sell"> Buy/Sell </option>
                                <option value="giveaway"> Giveaway </option>
                            </select>
                        </div>

                        <!-- book genre -->
                        <div class="filter-parameter">
                            <!-- heading -->
                            <div class="heading">
                                <label for="genre" class="form-label"> Genre </label>
                            </div>

                            <select class="form-select form-select-md" name="genre" id="genre"
                                aria-label="Small select example">
                                <?php
                                if ($filterState) {
                                    if ($filterGenre != 'all') {
                                        ?>
                                        <option value="<?= $filterGenre ?>" selected hidden> <?= $filterGenre ?> </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="all" selected hidden> All genre </option>
                                        <?php
                                    }
                                }
                                ?>
                                <option value="all"> All genre </option>
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
                $genreObj = new Genre();
                $genreList = [];
                $genreList = $genreObj->fetchGenreList();
                ?>

                <!-- fetch all the genres -->
                <div class="d-flex flex-row flex-wrap gap-2 genre-container">
                    <?php
                    if (sizeof($genreList) > 0) {
                        foreach ($genreList as $genre) {
                            ?>
                            <div class="genre">
                                <p class="m-0 text-secondary"> <?= $genre ?> </p>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="genre">
                            <p class="m-0 text-secondary"> No trending genre yet! </p>
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

                <?php
                // fetch user's book
                $userBookIdList = $bookObj->fetchUserBookId($userId);

                // fetch user wishlist
                $userWishlist = $wishlist->fetchWishlist();
                ?>

                <!-- all book container -->
                <div class="d-flex flex-row flex-wrap gap-3 all-book-container">
                    <?php
                    if (sizeof($bookIdList) > 0) {
                        foreach ($bookIdList as $bookId) {
                            // filtering
                            $bookObj->fetch($bookId);
                            if ($filterState) {
                                // purpose
                                if ($filterPurpose != "all") {
                                    if ($bookObj->purpose != $filterPurpose) {
                                        continue;
                                    }
                                }

                                // genre
                                if ($filterGenre != "all") {
                                    if (!in_array($filterGenre, $bookObj->genre)) {
                                        continue;
                                    }
                                }

                                // price
                                $rent = 0.20 * $bookObj->price['actual'];

                                if ($minPrice != 0 || $maxPrice != 0) {
                                    if ($minPrice != 0 && $maxPrice == 0) {
                                        if ($bookObj->purpose == 'renting') {
                                            if ($rent < $minPrice) {
                                                continue;
                                            }
                                        } elseif ($bookObj->purpose == 'buy/sell') {
                                            // buy/sell
                                            if ($bookObj->price['offer'] < $minPrice) {
                                                continue;
                                            }
                                        }
                                    } elseif ($minPrice == 0 && $maxPrice != 0) {
                                        if ($bookObj->purpose == 'renting') {
                                            if ($rent > $maxPrice) {
                                                continue;
                                            }
                                        } elseif ($bookObj->purpose == 'buy/sell') {
                                            // buy/sell
                                            if ($bookObj->price['offer'] > $maxPrice) {
                                                continue;
                                            }
                                        }
                                    } else {
                                        // both min and max price provided
                                        if ($bookObj->purpose == 'renting') {
                                            if ($rent < $minPrice || $rent > $maxPrice) {
                                                continue;
                                            }
                                        } elseif ($bookObj->purpose == 'buy/sell') {
                                            // buy/sell
                                            if ($bookObj->price['offer'] < $minPrice || $bookObj->price['offer'] > $maxPrice) {
                                                continue;
                                            }
                                        }
                                    }
                                }
                            } else {
                                // $bookObj->fetch($bookId);
                            }

                            ?>
                            <div class="book-container">
                                <!-- book image -->
                                <div class="book-image">
                                    <?php $bookObj->setCoverPhotoUrl(); ?>
                                    <img src="<?= $bookObj->photoUrl['cover'] ?>" alt="" loading="lazy">
                                </div>

                                <!-- book details -->
                                <div class="book-details">
                                    <!-- book title -->
                                    <div class="book-title-wishlist">
                                        <p class="book-title">
                                            <?= ucwords($bookObj->title) ?>
                                        </p>

                                        <?php
                                        if (!in_array($bookId, $userBookIdList)) {
                                            ?>
                                            <div class="wishlist">
                                                <a
                                                    href="/bookrack/app/wishlist-code.php?book-id=<?= $bookObj->getId() ?>&ref_url=<?= $url ?>">
                                                    <?php
                                                    if (in_array($bookId, $userWishlist)) {
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
                                    <p class="book-purpose"> <?= ucfirst($bookObj->purpose) ?> </p>

                                    <!-- book description -->
                                    <div class="book-description-container">
                                        <p class="book-description"> <?= ucfirst($bookObj->description) ?> </p>
                                    </div>

                                    <!-- book price -->
                                    <div class="book-price">
                                        <p class="book-price">
                                            <?php
                                            if ($bookObj->purpose == "renting") {
                                                $rent = $bookObj->price['actual'] * 0.20;
                                                echo "NPR." . number_format($rent, 2) . "/week";
                                            } elseif ($bookObj->purpose == "buy/sell") {
                                                $price = $bookObj->price['offer'];
                                                echo "NPR." . number_format($price, 2);
                                            }
                                            ?>
                                    </div>

                                    <button class="btn" onclick="window.location.href='/bookrack/book-details/<?= $bookId ?>'">
                                        Show More </button>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <p class="m-0 text-danger"> No book has been added yet!</p>
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

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <!-- jquery, bootstrap [cdn + local] -->
    <?php require_once __DIR__ . '/app/script-include.php'; ?>

    <script>
        // filter
        var filterTriggerState = false;
        const aside = $('#aside');
        const showFilter = $('#filter-show-trigger-2');
        const hideFilter = $('#filter-hide-trigger');

        // filter
        showFilter.on('click', function () {
            console.log("Show filter");
            aside.css({
                'display': 'block'
            });
            filterTriggerState = !filterTriggerState;
        });

        hideFilter.on('click', function () {
            aside.css({
                'display': 'none'
            });
            filterTriggerState = !filterTriggerState;
        });
    </script>
</body>

</html>