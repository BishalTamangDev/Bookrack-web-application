<?php

$response = false;

$userId = $_POST['userId'] ?? 0;
$cartId = $_POST['cartId'] ?? 0;

if($userId == 0 || $cartId == 0) {
    echo false;
    exit;
}

require_once __DIR__ . '/../../classes/cart.php';
require_once __DIR__ . '/../../classes/notification.php';

$tempCart = new Cart();
$tempNotification = new Notification();

date_default_timezone_set('Asia/Kathmandu');
$currentDate = date("Y:m:d H:i:s");

$cartExists = $tempCart->fetch($cartId);

if($cartExists) {
    // update arrived date
    $response = $tempCart->orderArrived($currentDate);

    if($response) {
        // notify user :: reader for cart arrival
        $response = $tempNotification->orderArrived($cartId, $userId, $currentDate);
    }
}

echo $response;