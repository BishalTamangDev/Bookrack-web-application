<?php
$output = "";

if (isset($_POST['userId']) && isset($_POST['bookId'])) {
    $userId = $_POST['userId'];
    $bookId = $_POST['bookId'];

    $task = "";

    require_once __DIR__ . '/../classes/wishlist.php';
    require_once __DIR__ . '/../classes/cart.php';

    $cart = new Cart();
    $cart->setUserId($userId);
    $bookExistsInCart = $cart->checkBookInCart($bookId);
    $output .= $bookExistsInCart ? "<a class='btn' id='cart-btn' data-task='remove'><i class='fa fa-shopping-cart'></i> Remove from cart </a>" : "<a class='btn' id='cart-btn' data-task='add'><i class='fa fa-shopping-cart'></i> Add to cart </a>";
}

echo $output;