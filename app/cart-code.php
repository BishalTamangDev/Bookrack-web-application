<?php
if(session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

if(isset($_GET['task']) && $_GET['bookId'] && isset($_GET['url'])){
    $userId = $_SESSION['bookrack-user-id'];
    $bookId = $_GET['bookId'];
    $task = $_GET['task'];
    $url = $_GET['url'];

    echo "task : $task<br/>";
    echo "user id : $userId<br/>";
    echo "book id : $bookId<br/>";

    require_once __DIR__ . '/cart-class.php';
    $cart = new Cart();
    $cart->setUserId($userId);
    $cart->setBookId($bookId);
    if($task == 'add') {
        $cart->addBook();
    } elseif ($task == 'remove') {
        $cart->removeBook($bookId);
    }

    if($url == 'cart') {
        header("Location: /bookrack/cart/current");
    } else {
        header("Location: /bookrack/book-details/$bookId");
    }
} else {
    header("Location: /bookrack/home");
}

exit();