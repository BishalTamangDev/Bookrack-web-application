<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['bookrack-user-id']))
    header("Location: /bookrack/home");
else
    $userId = $_SESSION['bookrack-user-id'];

require_once __DIR__ . '/connection.php';

if (isset($_POST['checkout-btn'])) {
    if (isset($_POST['url']) && isset($_POST['cart-id']) && isset($_POST['checkout-option'])) {
        global $database;
        $url = $_POST['url'];
        $cartId = $_POST['cart-id'];
        $checkoutOption = $_POST['checkout-option'];

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        if ($checkoutOption == 'click-and-collect' || $checkoutOption == 'cash-on-delivery') {
            // update the current cart details
            if ($checkoutOption == 'click-and-collect') {
                require_once __DIR__ . '/../classes/cart.php';
                require_once __DIR__ . '/../classes/book.php';

                $newBookList = [];
                $newBook = [
                    'id' => '',
                    'price' => '',
                    'arrived_date' => ''
                ];

                $cart = new Cart();
                require_once __DIR__ . '/../classes/cart.php';
                $bookObj = new Book();

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
                    'status' => 'pending'
                ];

                $response = $database->getReference("carts/{$cartId}")->update($postData);

                // update book status to on-hold
                foreach ($newBookList as $list) {
                    $bookId = $list['id'];
                    $bookObj->updateFlag($bookId, "on-hold");
                }

                // create notification for admin
                require_once __DIR__ . '/../classes/notification.php';

                $notificationObj = new Notification();
                $notificationObj->cartCheckout($userId, $cartId);

                header("Location: /bookrack/cart/pending");
            }
        } else {
            // redirect to the digital wallet page
        }
    } else {
        header("Location: /bookrack/cart/current");
    }
}

exit();
