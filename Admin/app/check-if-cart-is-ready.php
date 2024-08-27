<?php

$status = false;

$cartId = $_POST['cartId'] ?? 0;

if($cartId == 0) {
    echo $status;
    exit;
}

require_once __DIR__ . '/../../classes/cart.php';

$tempCart = new Cart();

$status = $tempCart->checkIfReady($cartId);

echo $status;