<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

// Validate CSRF token
if ($_POST['csrf_token_cart'] !== $_SESSION['csrf_token']) {
    echo false;
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
        $subTotal += $bookObj->priceOffer;
        $newBook = [
            'id' => $bookList['id'],
            'price' => $bookObj->priceOffer,
            'arrived_date' => ''
        ];
        $newBookList[] = $newBook;
    }

    date_default_timezone_set('Asia/Kathmandu');
    $currentDate = date("Y:m:d H:i:s");

    $postData = [
        'book_list' => $newBookList,
        'checkout_option' => $checkoutOption,
        'sub_total' => $subTotal,
        'shipping_charge' => 0,
        'date' => [
            'order_placed' => $currentDate,
            'order_confirmed' => '',
            'order_arrived' => '',
            'order_packed' => '',
            'order_shipped' => '',
            'order_delivered' => '',
            'order_completed' => ''
        ],
        'shipping_district' => '',
        'shipping_municipality' => '',
        'shipping_ward' => '',
        'shipping_tole_village' => '',
        'status' => 'pending'
    ];

    if ($checkoutOption == 'cash-on-delivery') {
        // fetch user address
        require_once __DIR__ . '/../classes/user.php';
        $user = new User();
        $user->fetch($userId);

        $postData['shipping_charge'] = $shippingCharge;
        $postData['shipping_district'] = $user->getAddressDistrict();
        $postData['shipping_municipality'] = $user->getAddressMunicipality();
        $postData['shipping_ward'] = $user->getAddressWard();
        $postData['shipping_tole_village'] = $user->getAddressToleVillage();
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