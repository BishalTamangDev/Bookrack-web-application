<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");

$userId = $_SESSION['bookrack-user-id'];

$ref_url = isset($_GET['ref_url']) ? $_GET['ref_url'] : "home";

if (isset($_GET['book-id'])) {
    $bookId = $_GET['book-id'];

    // check if the book id is valid
    require_once __DIR__ . '/../classes/user.php';
    $temp_user = new User();

    $userExist = $temp_user->fetch($userId);

    if ($userExist) {
        require_once __DIR__ . '/../classes/wishlist.php';
        $temp_wishlist = new Wishlist();
        $temp_wishlist->setUserId($userId);

        $status = $temp_wishlist->toggle($bookId);

        switch ($ref_url) {
            case 'profile':
                header("location: /bookrack/profile/wishlist");
                break;
            case 'book-details':
                header("location: /bookrack/book-details/$bookId");
                break;
            default:
                header("location: /bookrack/home");
        }
    }

}

exit;
