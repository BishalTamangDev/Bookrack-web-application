<?php

require_once __DIR__ . '/../../classes/request.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/cart.php';

$tempRequest = new Request();
$tempBook = new Book();
$tempUser = new User();

$requestList = $tempRequest->fetchAll();

if (sizeof($requestList) == 0) {
    ?>
    <tr>
        <td colspan="5" class="border text-danger"> No request yet! </td>
    </tr>
    <?php
} else {
    $serial = 1;
    foreach ($requestList as $request) {
        // fetch request
        $res = $tempRequest->fetch($request);

        $arrived = $tempRequest->dateSubmitted != '' ? true : false;

        // fetch book
        $bookId = $tempRequest->getBookId();
        $tempBook->fetch($bookId);

        $title = ucwords($tempBook->title);

        $isbn = $tempBook->isbn;

        // fetch user || owner details
        $ownerId = $tempBook->getOwnerId();
        $tempUser->fetch($ownerId);
        $ownerName = $tempUser->getFullName();

        // fetch cart id -> for redirection purpose
        $cartId = $tempRequest->cartId;
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
                        <button class="btn btn-brand" id="mark-as-arrived-btn" data-book-id="<?= $bookId ?>" data-cart-id="<?=$cartId?>"> Mark as arrived </button>
                        <?php
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php
    }
}
