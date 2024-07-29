<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// Validate CSRF token
if ($_POST['csrf_token_cart'] !== $_SESSION['csrf_token']) {
    echo 'Invalid CSRF token.';
    exit;
}

if (!isset($_SESSION['bookrack-user-id'])) {
    echo false;
    exit;
}

$status = false;
$userId = $_SESSION['bookrack-user-id'];

if (isset($_POST['cart-id']) && isset($_POST['checkout-option'])) {
    $shippingCharge = 50.00;
    $cartId = $_POST['cart-id'];
    $checkoutOption = $_POST['checkout-option'];

    // update the current cart details
    require_once __DIR__ . '/../classes/cart.php';
    require_once __DIR__ . '/../classes/book.php';
    require_once __DIR__ . '/../classes/notification.php';

    $cart = new Cart();
    $bookObj = new Book();
    $notificationObj = new Notification();

    $newBookList = [];
    $newBook = [
        'id' => '',
        'price' => '',
        'arrived_date' => ''
    ];

    // fetch cart
    if (!$cart->fetch($cartId))
        header("Location: /bookrack/home");

    // set book price
    $subTotal = 0;
    foreach ($cart->bookList as $bookList) {
        $bookObj->fetch($bookList['id']);
        $subTotal += $bookObj->price['offer'];
        $newBook = [
            'id' => $bookList['id'],
            'price' => $bookObj->price['offer'],
            'arrived_date' => ''
        ];
        $newBookList[] = $newBook;
    }

    $postData = [
        'book_list' => $newBookList,
        'checkout_option' => $checkoutOption,
        'sub_total' => $subTotal,
        'shipping_charge' => 0,
        'date' => [
            'order_placed' => date('Y:m:d h:i:s'),
            'order_confirmed' => '',
            'order_arrived' => '',
            'order_packed' => '',
            'order_shipped' => '',
            'order_delivered' => '',
            'order_completed' => ''
        ],
        'status' => 'pending'
    ];

    if ($checkoutOption == 'cash-on-delivery') {
        // fetch user address
        require_once __DIR__ . '/../classes/user.php';
        $user = new User();
        $user->fetch($userId);

        $postData['shipping_charge'] = $shippingCharge;
        $postData['shipping_address']['district'] = $user->getAddressDistrict();
        $postData['shipping_address']['location'] = $user->getAddressLocation();
    }

    $response = $database->getReference("carts/{$cartId}")->update($postData);

    // update book status to on-hold
    foreach ($newBookList as $list) {
        $bookId = $list['id'];
        $bookObj->updateFlag($bookId, "on-hold");
    }

    // create notification for admin
    $notificationObj->cartCheckout($userId, $cartId);

    $status = true;
}

echo $status;
exit;