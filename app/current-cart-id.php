<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id'])) {
    echo "";
    exit;
}

$userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/../classes/cart.php';
$currentCart = new Cart();

$currentCart->setUserId($userId);
$currentCartId = $currentCart->fetchCurrentCartId();

echo $currentCartId;
exit;