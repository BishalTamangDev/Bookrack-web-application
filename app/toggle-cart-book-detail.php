<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    exit;

if (isset($_SESSION['bookrack-user-id']) && isset($_POST['task']) && isset($_POST['bookId'])) {
    $userId = $_SESSION['bookrack-user-id'];
    $bookId = $_POST['bookId'];
    $task = $_POST['task'];

    require_once __DIR__ . '/../classes/cart.php';
    $cart = new Cart();
    $cart->setUserId($userId);
    $cart->setBookId($bookId);

    $status = ($task == 'add') ? $cart->addBook() : $cart->removeBook($bookId);
    echo $status;
}
exit;
