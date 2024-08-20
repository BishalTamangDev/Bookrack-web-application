<?php

require_once __DIR__ . '/../../classes/book.php';

$tempBook = new Book();

$idList = $tempBook->fetchLatestBookId();

if (sizeof($idList) == 0) {
    ?>
    <p class="f-reset text-danger"> No books found! </p>
    <?php
} else {
    foreach ($idList as $bookId) {
        $tempBook->fetch($bookId);
        $tempBook->setPhotoUrl();
        ?>
        <div class="recently-arrived-book" onclick="window.location.href='/bookrack/admin/admin-book-details/<?= $bookId ?>'">
            <div class="image-div">
                <img src="<?= $tempBook->photoUrl ?>" alt="book cover photo" loading="lazy">
            </div>

            <div class="detail">
                <div class="title">
                    <p> <?= ucWords($tempBook->title) ?> </p>
                </div>

                <div class="genre">
                    <?php

                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
?>