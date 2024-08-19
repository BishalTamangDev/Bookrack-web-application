<?php

$userId = $_POST['userId'] ?? 0;

if ($userId == 0)
    header("Location: /bookrack/landing");

require_once __DIR__ . '/../classes/book.php';

$tempBook = new Book();

$userBookList = $tempBook->fetchBookByUserId($userId);

if (sizeof($userBookList) == 0) {
    ?>
    <!-- empty context -->
    <section class="flex-column mt-3 gap-3 align-items-center empty-context-container" id="empty-context-container">
        <img src="assets/icons/empty.svg" alt="empty icon">
        <p class="m-0 text-danger"> You haven't added any book yet! </p>
    </section>
    <?php
} else {
    foreach ($userBookList as $bookId) {
        $tempBook->fetch($bookId);
        ?>
        <div class="book-container my-book my-book-active">
            <!-- book image -->
            <div class="book-image">
                <?php $tempBook->setPhotoUrl(); ?>
                <img src="<?= $tempBook->photoUrl ?>" alt="">
            </div>

            <!-- book details -->
            <div class="book-details">
                <!-- book title -->
                <div class="book-title-wishlist">
                    <p class="book-title"> <?= ucwords($tempBook->title) ?> </p>
                </div>

                <!-- book purpose -->
                <p class="book-purpose"> <?= ucfirst($tempBook->purpose) ?> </p>

                <!-- book description -->
                <div class="book-description-container">
                    <p class="book-description"> <?= ucfirst($tempBook->description) ?> </p>
                </div>

                <!-- book price -->
                <div class="book-price">
                    <p class="book-price">
                        <?php
                        if ($tempBook->purpose == "renting") {
                            $rent = 0.20 * $tempBook->price['actual'];
                            echo "NPR." . number_format($rent, 2) . "/week";
                        } else {
                            $price = $tempBook->price['offer'];
                            echo "NPR." . number_format($price, 2);
                        }
                        ?>
                    </p>
                </div>

                <button class="btn" onclick="window.location.href='/bookrack/book-details/<?= $tempBook->getId() ?>'">
                    Show More
                </button>
            </div>
        </div>
        <?php
    }
}
?>