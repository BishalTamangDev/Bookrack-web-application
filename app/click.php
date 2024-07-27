<?php
$status = false;
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id'])) {
    echo $status;
    exit;
}

if (isset($_POST['bookId'])) {
    $bookId = $_POST['bookId'];

    require_once __DIR__ . '/../classes/book.php';
    require_once __DIR__ . '/../classes/click.php';

    $book = new Book();
    $click = new Click();
    
    $book->fetch($bookId);

    $click->setId($_SESSION['bookrack-user-id']);
    $click->setNewGenreList($book->genre);
    $click->add();
    $status = true;
}

echo $status;

exit;