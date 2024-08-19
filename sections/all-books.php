<?php
$userId = $_POST['userId'] ?? 0;

if($userId == 0) {
    echo "Empty!";
    exit;
}

// wishlist object
require_once __DIR__ . '/../classes/book.php';
require_once __DIR__ . '/../classes/wishlist.php';

$allBookObj = new Book();

$contentCount = 5;

$page = $_POST['page'];
$offset = $_POST['offset'];
$searchContent = $_POST['searchContent'];
$genre = $_POST['genre'];
$minPrice = $_POST['minPrice'];
$maxPrice = $_POST['maxPrice'];

if ($searchContent != "") {
    // search content
    $allBookList = isset($_SESSION['book-id-list']) ? $_SESSION['book-id-list'] : $allBookList = $allBookObj->searchBookForHome($searchContent);
} else {
    $allBookList = isset($_SESSION['book-id-list']) ? $_SESSION['book-id-list'] : $allBookObj->fetchAllBookIdForHome();
}

$endIndex = $offset + $contentCount;

$userBookList = $allBookObj->fetchUserBookId($userId);

$wishlist = new Wishlist();
$wishlist->setUserId($userId);
$wishlistList = $wishlist->fetchWishlist();

$count = 0;
$serial = -1;

if (sizeof($allBookList) == 0) {
    ?>
    <!-- empty context -->
    <section class="flex-column mt-3 gap-3 align-items-center empty-context-container" id="empty-context-container">
        <img src="assets/icons/empty.svg" alt="empty icon">
        <p class="m-0 text-danger"> Empty! </p>
    </section>
    <?php
} else {
    foreach ($allBookList as $allBookId) {
        $serial++;
        if ($serial >= $offset && $serial <= $endIndex) {
            if ($count != $contentCount) {
                $count++;
                $allBookObj->fetch($allBookId);

                $finalGenreClasses = "";

                foreach ($allBookObj->genre as $genre) {
                    $genreClass = "";
                    $genreClass = str_replace("'", "", $genre);
                    $genreClass = preg_replace('/\s+/', "-", $genreClass);
                    $genreClass = preg_replace('/\(/', "", $genreClass);
                    $genreClass = preg_replace('/\)/', "", $genreClass);
                    $genreClass = $genreClass . '-element';
                    $finalGenreClasses .= $genreClass . ' ';
                }
                ?>
                <div class="book-container mb-3 <?= $finalGenreClasses ?> book-element" data-price="<?= $allBookObj->price['offer'] ?>">
                    <!-- book image -->
                    <div class="book-image">
                        <?php $allBookObj->setPhotoUrl(); ?>
                        <img src="<?= $allBookObj->photoUrl ?>" alt="" loading="lazy">
                    </div>

                    <!-- book details -->
                    <div class="book-details">
                        <!-- title & wishlist toggle -->
                        <div class="book-title-wishlist">
                            <!-- book title -->
                            <p class="book-title">
                                <?= ucwords($allBookObj->title) ?>
                            </p>

                            <?php
                            // check if user is the owner
                            if (!in_array($allBookId, $userBookList)) {
                                ?>
                                <div class="wishlist">
                                    <?php
                                    // toggle wishlist icon
                                    if (in_array($allBookId, $wishlistList)) {
                                        ?>
                                        <a> <i class="fa-solid fa-bookmark wishlist-toggle-icon" data-book-id="<?= $allBookId ?>"
                                                data-task="remove"></i> </a>
                                        <?php
                                    } else {
                                        ?>
                                        <a> <i class="fa-regular fa-bookmark wishlist-toggle-icon" data-book-id="<?= $allBookId ?>"
                                                data-task="add"></i> </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <!-- book author -->
                        <div class="book-author-container">
                            <p class="book-author">
                            <?php
                                $authorCount = sizeof($allBookObj->author);
                                $count = 0;
                                foreach ($allBookObj->author as $author) {
                                    $count++;
                                    echo ucwords($author);

                                    if ($count < $authorCount) {
                                        echo ", ";
                                    }
                                }
                                ?>
                            </p>
                        </div>

                        <!-- description -->
                        <div class="book-description-container">
                            <p class=""> <?=ucfirst($allBookObj->description)?> </p>
                        </div>

                        <!-- book price -->
                        <div class="book-price">
                            <p class="book-price">
                                <?php
                                    $price = $allBookObj->price['offer'];
                                    echo "NPR." . number_format($price, 2);
                                ?>
                            </p>
                        </div>

                        <?php $isOwner = in_array($allBookId, $userBookList) ? "true" : "false"; ?>

                        <a class="btn show-more-btn" data-book-id="<?= $allBookId ?>" data-is-owner="<?= $isOwner ?>">
                            Show More </a>
                    </div>
                </div>
                <?php
            }
        }
    }

    if (sizeof($allBookList) > $endIndex) {
        ?>
        <div class="w-100 mt-2 load-more-btn-container" id="load-more-btn-container">
            <button class="load-more-btn" id="load-more-btn" data-offset="<?= $offset + $contentCount ?>"> Load More </button>
        </div>
        <?php
    }
}