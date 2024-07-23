<?php

require_once __DIR__ . '/connection.php';
class Cart
{
    // private data
    private $cartId;
    private $userId;
    public $shippingCharge;
    public $subTotal;

    // public data
    public $bookList = [];

    public $book = [
        'id' => '',
        'price' => '',
        'arrived_date' => ''
    ];
    public $date = [
        'order_placed' => '',
        'order_confirmed' => '',
        'order_arrived' => '',
        'order_packed' => '',
        'order_shipped' => '',
        'order_delivered' => '',
        'order_completed' => ''
    ];

    public $shippingAddress = [
        'district' => '',
        'location' => ''
    ];
    public $checkoutOption;
    public $status;

    // getters
    public function getId()
    {
        return $this->cartId;
    }

    public function userId()
    {
        return $this->userId;
    }

    // setters
    public function setId($id)
    {
        $this->cartId = $id;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setBookId($bookId)
    {
        $this->book = [
            'id' => $bookId,
            'price' => '',
            'arrived_date' => ''
        ];

        $this->bookList[] = $this->book;
    }

    // constructor
    public function __construct()
    {
        $this->cartId = '';
        $this->userId = '';

        $this->bookList = [];

        $this->shippingCharge = 0;
        $this->subTotal = 0;

        $this->book = [
            'id' => '',
            'price' => '',
            'arrived_date' => ''
        ];

        $this->date = [
            'order_placed' => '',
            'order_confirmed' => '',
            'order_arrived' => '',
            'order_packed' => '',
            'order_shipped' => '',
            'order_delivered' => '',
            'order_completed' => ''
        ];

        $this->shippingAddress = [
            'district' => '',
            'location' => ''
        ];

        $this->checkoutOption = '';

        $this->status = '';
    }

    // setCart
    public function set($cartId, $userId, $bookList, $date, $shippingCharge, $subTotal, $checkoutOption, $status)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
        $this->bookList = $bookList;
        $this->date = $date;
        $this->shippingCharge = $shippingCharge;
        $this->subTotal = $subTotal;
        $this->checkoutOption = $checkoutOption;
        $this->status = $status;
    }

    // add book to cart
    public function addBook()
    {
        global $database;
        $bookAdded = false;
        // check for unproceeded cart
        $response = $database->getReference("carts")->orderByChild('user_id')->equalTo($this->userId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if ($res['status'] == 'current') {
                    // fetch data
                    $bookList = [];

                    if (isset($res['book_list'])) {
                        foreach ($res['book_list'] as $list) {
                            $bookList[] = $list;
                        }
                        $bookList[] = $this->book;
                    } else {
                        $bookList[] = $this->book;
                    }

                    $postData = [
                        'user_id' => $this->userId,
                        'book_list' => $bookList,
                    ];

                    $postRef = $database->getReference("carts/{$key}")->update($postData);

                    $bookAdded = true;

                    break;
                }
            }

            if (!$bookAdded) {
                $postData = [
                    'user_id' => $this->userId,
                    'book_list' => $this->bookList,
                    'shipping_charge' => 0,
                    'sub_total' => 0,
                    'date' => [
                        'order_placed' => '',
                        'order_confirmed' => '',
                        'order_arrived' => '',
                        'order_packed' => '',
                        'order_shipped' => '',
                        'order_delivered' => '',
                        'order_completed' => ''
                    ],
                    'shipping_address' => [
                        'district' => '',
                        'location' => ''
                    ],
                    'checkout_option' => '',
                    'status' => 'current'
                ];
                $postRef = $database->getReference("carts/")->push($postData);
                $bookAdded = true;
            }
        } else {
            $postData = [
                'user_id' => $this->userId,
                'book_list' => $this->bookList,
                'shipping_charge' => 0,
                'sub_total' => 0,
                'date' => [
                    'order_placed' => '',
                    'order_confirmed' => '',
                    'order_arrived' => '',
                    'order_packed' => '',
                    'order_shipped' => '',
                    'order_delivered' => '',
                    'order_completed' => ''
                ],
                'shipping_address' => [
                    'district' => '',
                    'location' => ''
                ],
                'checkout_option' => '',
                'status' => 'current'
            ];
            $postRef = $database->getReference("carts/")->push($postData);
            $bookAdded = true;
        }

        return $bookAdded ? true : false;
    }

    // remove book from cart
    public function removeBook($bookId)
    {
        global $database;
        $bookRemoved = false;

        // check for current cart
        $response = $database->getReference("carts")->orderByChild('user_id')->equalTo($this->userId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if ($res['status'] == 'current') {
                    // fetch data
                    $bookList = [];
                    if (isset($res['book_list'])) {
                        foreach ($res['book_list'] as $list) {
                            if ($list['id'] != $bookId)
                                $bookList[] = $list;
                        }
                    }

                    $postData = [
                        'book_list' => $bookList,
                    ];

                    $postRef = $database->getReference("carts/{$key}")->update($postData);

                    $bookRemoved = true;
                    break;
                }
            }
        }
        return $bookRemoved ? true : false;
    }


    // fetch carts
    public function fetchCurrent()
    {
        global $database;
        $cartFound = false;
        $reference = $database->getReference("carts")->orderByChild("user_id")->equalTo($this->userId);

        if ($reference) {
            $response = $reference->getSnapshot()->getValue();
            // print_r($response);

            foreach ($response as $key => $res) {
                if ($res['status'] == 'current') {
                    $cartFound = true;
                    $this->cartId = $key;
                    if (isset($res['book_list'])) {
                        foreach ($res['book_list'] as $list) {
                            $this->book = [
                                'id' => $list['id'],
                                'arrived_date' => $list['arrived_date'],
                            ];
                            $this->bookList[] = $this->book;
                        }
                    }
                }
            }
        }
    }

    // fetch cart
    public function fetch($cartId)
    {
        global $database;
        $status = false;
        $response = $database->getReference("carts")->getChild($cartId)->getSnapshot()->getValue();
        if ($response) {
            $this->set($cartId, $response['user_id'], $response['book_list'], $response['date'], $response['shipping_charge'], $response['sub_total'], $response['checkout_option'], $response['status']);
            $status = true;
        }
        return $status;
    }

    // chek if the product is in the cart :: current && pending
    public function checkBookInCart($bookId)
    {
        global $database;
        $bookFound = false;
        $response = $database->getReference("carts")->orderByChild('user_id')->equalTo($this->userId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if ($res['status'] == 'current' || $res['status'] == 'pending') {
                    // check for book id
                    if (isset($res['book_list'])) {
                        foreach ($res['book_list'] as $existingBook) {
                            if ($existingBook['id'] == $bookId) {
                                $bookFound = true;
                            }
                        }
                        break;
                    }
                }
            }
        }

        return $bookFound ? true : false;
    }

    // fetch pending cart
    public function fetchPendingCartId()
    {
        global $database;
        $cartFound = false;
        $pendingCarts = [];
        $reference = $database->getReference("carts")->orderByChild("user_id")->equalTo($this->userId);

        if ($reference) {
            $response = $reference->getSnapshot()->getValue();

            foreach ($response as $key => $res) {
                if ($res['status'] == 'pending')
                    $pendingCarts[] = $key;
            }
        }

        return $pendingCarts;
    }
}