<?php

require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';

$tempUser = new User();
$tempBook = new Book();

// fetch all users
$bookIdList = $tempBook->fetchAllBookId();

$searchCount = 0;
$serial = 1;
foreach ($bookIdList as $bookId) {
    $bookExists = $tempBook->fetch($bookId);
    if ($tempBook->flag == 'on-hold') {
        $searchCount++;
        $tempUser->fetch($tempBook->getOwnerId());
        $ownerName = $tempUser->getFullName();
        ?>
        <tr class="book-tr">
            <th scope="row" class="border"> <?= $serial++ ?> </th>
            <td class="border">
                <a href="/bookrack/admin/admin-book-details/<?= $bookId ?>" class="text-primary">
                    <?= ucWords($tempBook->title) ?>
                </a>
            </td>
            <td class="border"> <?= $tempBook->isbn ?> </td>
            <td class="border">
                <a href="/bookrack/admin/admin-user-details/<?= $userId ?>" class="text-primary">
                    <?= $ownerName ?>
                </a>
            </td>
            <td class="border">
                <div class="d-flex flex-row">
                    <button class="btn btn-brand"> Mark as arrived </button>
                </div>
            </td>
        </tr>
        <?php
    }
}

if ($searchCount == 0) {
    ?>
    <tr>
        <td colspan="5" class="text-danger border"> No book found! </td>
    </tr>
    <?php
}