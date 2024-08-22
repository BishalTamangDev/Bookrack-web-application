<?php 

$count = 0;

require_once __DIR__ . '/../../classes/cart.php';

$tempCart = new Cart();

$count = $tempCart->countCompletedCartId();

echo $count;