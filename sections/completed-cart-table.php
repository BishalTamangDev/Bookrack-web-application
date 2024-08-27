<?php

$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
    echo "error";
    // echo false;
    exit;
}

require_once __DIR__ . '/../classes/cart.php';
require_once __DIR__ . '/../classes/book.php';

$tempCart = new Cart();
$tempBook = new Book();

$cartList = $tempCart->fetchCompletedCartOfUser($userId);

if (sizeof($cartList) == 0) {
    echo "No cart found!";
} else {
    $serial = 1;
    foreach ($cartList as $cart) {
        $cartId = $cart['cart_id'];
        $initiatedDate = $cart['date']['order_placed'];
        $completedDate = $cart['date']['order_completed'];

        // book list
        ?>
        <tr>
            <td> <?= $serial++ ?> </td>
            <td>
                <?= $cartId ?>
            </td>
            <td>
                <ol class="m-0 p-0">
                    <?php
                    foreach ($cart['book_list'] as $book) {
                        $tempBook->fetch($book['id']);
                        $title = ucwords($tempBook->title);
                        ?>
                        <li class="list-group-item"><?= $title ?></li>
                        <?php
                    }
                    ?>
                </ol>
            </td>
            <td> <?= $initiatedDate ?> </td>
            <td> <?= $completedDate ?> </td>
        </tr>
        <?php
    }
}