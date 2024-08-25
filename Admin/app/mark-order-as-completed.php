<?php

$cartId = $_POST['cartId'] ?? 0;
$userId = $_POST['userId'] ?? 0;

if ($cartId == 0 || $userId == 0) {
    echo false;
    exit;
}

require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/book.php';
require_once __DIR__ . '/../../classes/notification.php';

$tempCart = new Cart();
$tempBook = new Book();
$tempNotification = new Notification();

date_default_timezone_set('Asia/Kathmandu');
$currentDate = date("Y:m:d H:i:s");

// fetch cart
$tempCart->fetch($cartId);

$status = $tempCart->orderCompleted($currentDate);

if ($status) {
    // update book status/ flag to sold-out
    foreach($tempCart->bookList as $bookList) {
        $tempBook->sell($bookList['id']);
    }

    // notify user :: reader as their book has been packed
    $status = $tempNotification->orderCompleted($cartId, $userId, $currentDate);
}

echo $status;