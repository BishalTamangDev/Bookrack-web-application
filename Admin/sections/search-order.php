<?php

$content = $_POST['content'] ?? 0;

if ($content == 0) {
    exit;
}

echo "Content : $content\n";

require_once __DIR__ . '/../../classes/request.php';
require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';

$tempRequest = new Request();
$tempCart = new Cart();
$tempBook = new Book();
$tempUser = new User();

$list = $tempRequest->fetchAll();

$searchList = [];

foreach ($list as $request) {
    $tempRequest->fetch($request);

    $tempCart->fetch($tempRequest->cartId);

    $cartId = $tempRequest->cartId;

    foreach ($tempCart->bookList as $book) {
        $tempBook->fetch($book['id']);

        $title = $tempBook->title;

        if (strpos($title, $content) !== false || strcasecmp($cartId,$content) == 0) {
            $searchList[] = $tempCart;
        }
    }
}

$serial = 1;

if (sizeof($searchList) == 0) {
    ?>
    <tr>
        <td colspan="6"> No order found! </td>
    </tr>
    <?php
} else {
    foreach ($searchList as $search) {
        $tempUser->fetch($search->getUserId());

        $userName = $tempUser->getFullName();

        $cartId = $tempCart->getId();

        $orderPlaced = $tempCart->date['order_placed'];

        $orderCompleted = $tempCart->date['order_completed'];

        $status = $tempCart->status;

        ?>

        <tr class="order-status-completed">
            <td> <?= $serial++ ?> </td>

            <td>
                <a href="/bookrack/admin/admin-order-summary/<?= $cartId ?>" class="text-primary small">
                    <?= $cartId ?>
                </a>
            </td>

            <td>
                <a href="/bookrack/admin/admin-user-details/<?= $tempCart->getUserId() ?>" class="text-primary">
                    <?= $userName ?>
                </a>
            </td>
            <td> <?= $orderPlaced ?> </td>
            <td> <?= $orderCompleted != "" ? $orderCompleted : '-' ?> </td>
            <td> <?= ucwords($status) ?> </td>
        </tr>
        <?php
    }
}
