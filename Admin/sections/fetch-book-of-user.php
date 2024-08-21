<?php

$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
    echo 'No book found!';
    exit;
}

require_once __DIR__ . '/../../classes/book.php';

$tempBook = new Book();

$bookIdList = $tempBook->fetchBookByUserId($userId);


if (sizeof($bookIdList) == 0) {
    ?>
    <tr>
        <td colspan="4" class="text-danger"> No book found! </td>
    </tr>
    <?php
} else {
    $count = 1;
    foreach ($bookIdList as $bookId) {
        $tempBook->fetch($bookId);
        ?>
        <tr>
            <td> <?= $count++ ?> </td>
            <td> <?= ucwords($tempBook->title) ?> </td>
            <td class="text-success fw-semibold"> <?= "NPR. " . number_format($tempBook->price['offer'], 2) ?> </td>
            <td> <?= ucfirst($tempBook->flag) ?> </td>
        </tr>
        <?php
    }
}