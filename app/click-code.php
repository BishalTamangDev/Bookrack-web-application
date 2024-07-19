<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

if (isset($_GET['bookId']) && isset($_GET['ownerId'])) {
    $bookId = $_GET['bookId'];
    $ownerId = $_GET['ownerId'];


    if ($ownerId != $_SESSION['bookrack-user-id']) {
        require_once __DIR__ . '/click-class.php';
        require_once __DIR__ . '/book-class.php';

        $click = new Click();
        $click->setId($_SESSION['bookrack-user-id']);

        $book = new Book();
        $book->fetch($bookId);

        $click->setNewGenreList($book->genre);

        $click->add();
    }
    header("Location: /bookrack/book-details/$bookId");
} else {
    header("Location: /bookrack/home");
}