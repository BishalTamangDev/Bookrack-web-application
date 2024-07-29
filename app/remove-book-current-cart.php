<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    exit;

if (isset($_SESSION['bookrack-user-id']) && isset($_POST['bookId'])) {
    $bookId = $_POST['bookId'];

    require_once __DIR__ . '/../classes/cart.php';
    $cart = new Cart();
    $cart->setUserId($_SESSION['bookrack-user-id']);

    $status = $cart->removeBook($bookId);
    echo $status;
}
exit;