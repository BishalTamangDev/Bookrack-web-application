<?php

$cartId = $_POST['cartId'] ?? 0;
$userId = $_POST['userId'] ?? 0;

if ($cartId == 0 || $userId == 0) {
    echo false;
    exit;
}

require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/notification.php';

$tempCart = new Cart();
$tempNotification = new Notification();

$currentDate = date('y-m-d h:i:s');

// fetch cart
$tempCart->fetch($cartId);

$status = $tempCart->orderPacked($currentDate);

if ($status) {
    // notify user :: reader as their book has been packed
    $status = $tempNotification->orderPacked($cartId, $userId, $currentDate);
}

echo $status;