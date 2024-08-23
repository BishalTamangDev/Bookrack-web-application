<?php

$content = $_POST['content'] ?? '';

if ($content == '') {
    ?>
    <tr>
        <td colspan="4" class="text-danger border"> No book found! </td>
    </tr>
    <?php
    exit;
}

require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/request.php';

$tempUser = new User();
$tempBook = new Book();
$tempRequest = new Request();

// fetch all users
$bookIdList = $tempBook->searchBook($content);

$searchCount = 0;
$serial = 1;

foreach ($bookIdList as $bookId) {
    // search this book in request list
    $isRequested = $tempRequest->searchById($bookId);

    if ($isRequested) {
        $searchCount++;

        // fetch request detail
        $tempRequest->fetchRequestByBookId($bookId);

        $arrived = $tempRequest->date['submitted'] != '' ? true : false;

        print_r($tempRequest);

        // fetch book
        $tempBook->fetch($bookId);
        $title = ucwords($tempBook->title);
        $isbn = $tempBook->isbn;

        // fetch user || owner details
        $ownerId = $tempBook->getOwnerId();
        $tempUser->fetch($ownerId);
        $ownerName = $tempUser->getFullName();
        ?>

        <tr class="book-tr">
            <th scope="row" class="border"> <?= $serial++ ?> </th>
            <td class="border">
                <a href="/bookrack/admin/admin-book-details/<?= $bookId ?>" class="text-primary">
                    <?= $title ?>
                </a>
            </td>
            <td class="border"> <?= $tempBook->isbn ?> </td>
            <td class="border">
                <a href="/bookrack/admin/admin-user-details/<?= $ownerId ?>" class="text-primary">
                    <?= $ownerName ?>
                </a>
            </td>
            <td class="border">
                <div class="d-flex flex-row">
                    <?php
                    if($arrived) {
                        ?>
                        <p class="m-0"> Book already arrived </p>
                        <?php                         
                    } else {
                        ?>
                        <button class="btn btn-brand" id="mark-as-arrived-btn" data-book-id="<?= $bookId ?>"> Mark as arrived </button>
                        <?php
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
}

if ($searchCount == 0) {
    ?>
    <tr>
        <td colspan="5" class="border text-danger"> This book hasn't been requested! </td>
    </tr>
    <?php
}