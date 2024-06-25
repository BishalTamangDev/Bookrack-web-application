<?php
// starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['bookrack-user-id'])) {
    header("Location: /bookrack/home");
}


if (isset($_GET['book-id'])) {
    $bookId = $_GET['book-id'];

    $ref_url = $_GET['ref_url'];

    // check if the book id is valid
    require_once __DIR__ . '/user-class.php';
    require_once __DIR__ . '/book-class.php';

    $temp_book = new Book();
    $temp_user = new User();

    $userId = $_SESSION['bookrack-user-id'];

    $userExist = $temp_user->fetch($userId);
    $bookExist = $temp_book->fetch($bookId);

    if ($userExist && $bookExist) {
        require_once __DIR__ . '/wishlist-class.php';
        $temp_wishlist = new Wishlist();
        $temp_wishlist->setUserId($userId);

        $status = $temp_wishlist->toggle($bookId);

        if ($ref_url == "home") {
            header("location: /bookrack/home");
        } elseif ($ref_url == 'profile') {
            header("location: /bookrack/profile/wishlist");
        } elseif ($ref_url == 'book-details') {
            header("location: /bookrack/book-details/$bookId");
        }
    }
}

exit();
