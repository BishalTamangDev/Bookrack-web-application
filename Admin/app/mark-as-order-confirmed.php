<?php

$cartId = $_POST['cartId'] ?? 0;

if ($cartId == 0) {
    echo "error";
    exit;
}

require_once __DIR__ . '/../../classes/notification.php';
require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/request.php';

$tempUser = new User();
$tempCart = new Cart();
$tempBook = new Book();
$tempRequest = new Request();
$tempNotification = new Notification();

// update order status
$cartFound = $tempCart->fetch($cartId);

$status = $tempCart->confirmOrder();

$status = true;

if ($status) {
    // notify providers
    print_r($tempCart->bookList);

    foreach ($tempCart->bookList as $book) {
        $tempBook->fetch($book['id']);

        $ownerId = $tempBook->getOwnerId();

        $price = $tempBook->price['offer'];

        $currentDate = date('y-m-d h:i:s');

        // request
        $tempRequest->request($book['id'], $price, $tempCart->getUserId(), $currentDate);

        // make notification
        $tempNotification->requestBook($ownerId, $book['id']);
    }

    // notify reader
    $tempNotification->orderConfirmed($tempCart->getUserId(), $cartId);
}

echo $status ? true : false;