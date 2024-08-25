<?php

$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
    echo "Empty!";
    exit;
}

require_once __DIR__ . '/../classes/wishlist.php';
require_once __DIR__ . '/../classes/book.php';

$wishlist = new Wishlist();
$bookObj = new Book();

$wishlist->setUserId($userId);
$userWishlist = $wishlist->fetchWishlist();
?>

<div class="d-flex flex-column gap-4 my-book-content wishlist-content">
    <?php
    if (sizeof($userWishlist) == 0) {
        ?>
        <div class="empty-div">
            <img src="/bookrack/assets/icons/empty.svg" alt="" loading="lazy">
            <p class="empty-message"> Your wishlist is empty! </p>
        </div>
        <?php
    } else {
        ?>
        <div class="d-flex flex-row flex-wrap gap-3 wishlist-container">
            <?php
            foreach ($userWishlist as $wishedBookId) {
                $bookObj->fetch($wishedBookId);
                ?>
                <div class="book-container">
                    <!-- book image -->
                    <div class="book-image">
                        <?php $bookObj->setPhotoUrl(); ?>

                        <img src="<?= $bookObj->photoUrl ?>" alt="book photo" loading="lazy">
                    </div>

                    <!-- book details -->
                    <div class="book-details">
                        <!-- book title -->
                        <div class="book-title-wishlist">
                            <!-- book title -->
                            <div class="title-div">
                                <abbr title="<?= ucwords($bookObj->title) ?>">
                                    <p class="book-title"> <?= ucwords($bookObj->title) ?> </p>
                                </abbr>
                            </div>

                            <div class="wishlist">
                                <?php
                                // toggle wishlist icon
                                if (in_array($wishedBookId, $userWishlist)) {
                                    ?>
                                    <a> <i class="fa-solid fa-bookmark wishlist-toggle-icon" data-book-id="<?= $wishedBookId ?>"
                                            data-task="remove"></i> </a>
                                    <?php
                                } else {
                                    ?>
                                    <a> <i class="fa-regular fa-bookmark wishlist-toggle-icon" data-book-id="<?= $wishedBookId ?>"
                                            data-task="add"></i> </a>
                                    <?php
                                }
                                ?>
                                </a>
                            </div>
                        </div>

                        <!-- book authors -->
                        <div class="book-author-container">
                            <p class="book-author">
                                <?php
                                $authorCount = sizeof($bookObj->author);
                                $count = 0;
                                foreach ($bookObj->author as $author) {
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
                            <p class=""> <?= ucfirst($bookObj->description) ?> </p>
                        </div>

                        <!-- book price -->
                        <div class="book-price">
                            <p class="book-price">
                                <?php
                                $price = $bookObj->priceOffer;
                                echo "NPR. " . number_format($price, 2);
                                ?>
                            </p>
                        </div>

                        <button class="btn" onclick="window.location.href='/bookrack/book-details/<?= $wishedBookId ?>'"> Show
                            More
                        </button>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>