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
    <section class="flex-column gap-3 align-items-center empty-context-container" id="empty-context-container">
        <img src="assets/icons/empty.svg" alt="empty icon">
        <p class="m-0 text-danger"> You haven't added any book yet! </p>
    </section>
    <?php
} else {
    foreach ($userBookList as $bookId) {
        $tempBook->fetch($bookId);
        
        $statusClass = "";
        switch($tempBook->flag) {
            case 'verified':
                $statusClass = "on-stock-book";
                break;
            case 'on-hold':
                $statusClass = "on-hold-book";
                break;
            case 'sold-out':
                $statusClass = 'sold-out-book';
                break;
            default:
                $statusClass = "";
        }
        ?>
        <div class="book-container my-book <?=$statusClass?>">
            <!-- book image -->
            <div class="book-image">
                <?php $tempBook->setPhotoUrl(); ?>
                <img src="<?= $tempBook->photoUrl ?>" alt="">
            </div>

            <!-- book details -->
            <div class="book-details">
                <!-- book title -->
                <div class="book-title-wishlist">
                    <div class="title-div">
                        <abbr title="<?= ucwords($tempBook->title) ?>">
                            <p class="book-title"> <?= ucwords($tempBook->title) ?> </p>
                        </abbr>
                    </div>
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
                        $price = $tempBook->price['offer'];
                        echo "NPR. " . number_format($price, 2);
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