<?php

require_once __DIR__ . '/../../classes/book.php';

$tempBook = new Book();

// fetch all users
$bookIdList = $tempBook->fetchAllBookId();

if (sizeof($bookIdList) == 0) {
    ?>
    <tr>
        <td colspan="9" class="text-danger"> No book found! </td>
    </tr>
    <?php
} else {
    $serial = 1;
    foreach ($bookIdList as $bookId) {
        $tempBook->fetch($bookId);
        $ownerName = "-";
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
            </a>
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

exit;