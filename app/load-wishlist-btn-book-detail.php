<?php
$output = "";

if (isset($_POST['userId']) && isset($_POST['bookId'])) {
    $userId = $_POST['userId'];
    $bookId = $_POST['bookId'];

    require_once __DIR__ . '/../classes/wishlist.php';
    $wishlist = new Wishlist();
    $wishlist->setUserId($userId);
    $userWishlist = $wishlist->fetchWishlist();

    if (in_array($bookId, $userWishlist)) {
        $output .= "<a class='btn' id='wishlist-btn' data-task='remove'>";
        $output .= "<i class='fa-solid fa-bookmark'></i> Remove from wishlist";
        $output .= "</a>";
    } else {
        $output .= "<a class='btn' id='wishlist-btn' data-task='add'>";
        $output .= "<i class='fa-regular fa-bookmark'></i> Add to wishlist";
        $output .= "</a>";
    }
}

echo $output;