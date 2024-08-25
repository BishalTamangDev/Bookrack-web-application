<?php

$bookId = $_POST['bookId'] ?? 0;

if ($bookId == 0) {
    echo false;
    exit;
}

require_once __DIR__ . '/../../classes/request.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/notification.php';

$tempBook = new Book();
$tempRequest = new Request();
$tempCart = new Cart();
$tempNotification = new Notification();

// get owner id
$bookExists = $tempBook->fetch($bookId);

$ownerId = $tempBook->getOwnerId();

// get cart with this book
$cartId = $tempCart->getCartWithBookId($bookId);

date_default_timezone_set('Asia/Kathmandu');
$currentDate = date("Y:m:d H:i:s");

$tempCart->fetch($cartId);

// update book status in cart
$status = $tempCart->markBookAsArrived($bookId, $currentDate);

if ($status) {
    // update request table
    $requestId = $tempRequest->fetchRequestWithBookId($bookId);

    $status = $tempRequest->markBookAsArrived($requestId, $currentDate);

    if ($status) {
        // notify provider as their book has been received
        $status = $tempNotification->bookReceived($bookId, $ownerId, $currentDate);
    }
}

echo $status;