<?php

require_once __DIR__ . '/connection.php';
class Cart
{
    // private data
    private $cartId;
    private $userId;

    // public data
    public $bookList = [];

    public $book = [
        'id' => '',
        'arrived_date' => ''
    ];
    public $date = [
        'proceed' => '',
        'approved' => ''
    ];
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
            'arrived_date' => ''
        ];

        $this->bookList[] = $this->book;
    }

    // constructor
    public function __construct()
    {
        $cartId = '';
        $userId = '';

        $bookList = [];

        $book = [
            'id' => '',
            'arrived_date' => ''
        ];
        $date = [
            'proceed' => '',
            'approved' => ''
        ];
        $status = '';
    }

    // setCart
    public function set($cartId, $userId, $bookList, $date, $status)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
        $this->bookList = $bookList;
        $this->date = $date;
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
                    'date' => [
                        'proceed' => '',
                        'approved' => ''
                    ],
                    'status' => 'current'
                ];
                $postRef = $database->getReference("carts/")->push($postData);
                $bookAdded = true;
            }
        } else {
            $postData = [
                'user_id' => $this->userId,
                'book_list' => $this->bookList,
                'date' => [
                    'proceed' => '',
                    'approved' => ''
                ],
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
                    if(isset($res['book_list'])) {
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
        
        if($reference) {
            $response = $reference->getSnapshot()->getValue();
            // print_r($response);
            
            foreach($response as $res) {
                if($res['status'] == 'current') {
                    $cartFound = true;
                    if(isset($res['book_list'])) {
                        foreach($res['book_list'] as $list) {
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
}