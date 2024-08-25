<?php
require_once __DIR__ . '/../classes/book.php';

$allBookObj = new Book();

$allBookList = $allBookObj->fetchAllBookIdForHome();

if (sizeof($allBookList) == 0) {
    ?>
    <!-- empty context -->
    <section class="flex-column mt-3 gap-3 align-items-center empty-context-container" id="empty-context-container">
        <img src="assets/icons/empty.svg" alt="empty icon">
        <p class="m-0 text-danger"> Empty! </p>
    </section>
    <?php
} else {
    $serial = 0;
    foreach ($allBookList as $allBookId) {
        $serial++;
        $allBookObj->fetch($allBookId);
        if ($serial == 4) {
            break;
        }
        ?>
        <div class="book-container mb-3 book-element" data-price="<?= $allBookObj->priceOffer ?>">
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
                    <div class="title-div">
                        <abbr title="<?= ucwords($allBookObj->title) ?>">
                            <p class="book-title"> <?= ucwords($allBookObj->title) ?> </p>
                        </abbr>
                    </div>
                </div>

                <!-- description -->
                <div class="book-description-container">
                    <p class=""> <?= ucfirst($allBookObj->description) ?> </p>
                </div>

                <!-- book price -->
                <div class="book-price">
                    <p class="book-price">
                        <?php
                        $price = $allBookObj->priceOffer;
                        echo "NPR. " . number_format($price, 2);
                        ?>
                    </p>
                </div>

                <a href="/bookrack/book-details/<?= $allBookId ?>" class="btn show-more-btn"
                    data-book-id="<?= $allBookId ?>"> Show More </a>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="w-100 mt-2 load-more-btn-container" id="load-more-btn-container">
        <button class="btn btn-brand" id="load-more-btn" data-bs-toggle="modal" data-bs-target="#exampleModal"> Load More
        </button>
    </div>
    <?php
}