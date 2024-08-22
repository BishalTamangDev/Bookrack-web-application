<?php

$content = $_POST['content'] ?? '';

if ($content == '') {
    echo "No book found!";
    exit;
}

require_once __DIR__ . '/../../classes/book.php';

$tempBook = new Book();

// fetch all users
$bookIdList = $tempBook->fetchAllBookId();
$searchCount = 0;

$serial = 1;
foreach ($bookIdList as $bookId) {
    $tempBook->fetch($bookId);
    if ($content == $tempBook->title) {
        $searchCount++;
        ?>
        <tr class="book-tr <?php
        if ($tempBook->flag == 'verified')
            echo 'available-tr';
        elseif ($tempBook->flag == 'on-stock')
            echo 'on-stock-tr';
        elseif ($tempBook->flag == 'sold-out')
            echo 'sold-out-tr';
        ?> <?= "{$tempBook->language}-tr" ?>">
            <th scope="row"> <?= $serial++ ?> </th>
            <td> <?= ucWords($tempBook->title) ?> </td>
            <td> <?= $tempBook->isbn ?> </td>
            <td>
                <?php
                $count = 0;
                foreach ($tempBook->genre as $genre) {
                    $count++;
                    echo $count != count($tempBook->genre) ? $genre . ', ' : $genre;
                }
                ?>
            </td>
            <td> <?php foreach ($tempBook->author as $author)
                echo ucWords($author) . ', '; ?> </td>
            <td> <?= ucfirst($tempBook->language) ?></td>

            <td> <?= $tempBook->flag ?></td>
            <td>
                <abbr title="Show full details">
                    <a href="/bookrack/admin/admin-book-details/<?= $bookId ?>" class="text-primary small">
                        Show detail
                    </a>
                </abbr>
            </td>
        </tr>
        <?php
    }
}

if ($searchCount == 0) {
    ?>
    <tr>
        <td colspan="8">
            <div class="d-flex flex-row gap-2 table-loading-gif-container">
                <p class="m-0 text-secondary"> No book found! </p>
            </div>
        </td>
    </tr>
    <?php
}