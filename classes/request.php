<?php

require_once __DIR__ . '/../app/connection.php';

class Request
{
    public $requestId;
    private $ownerId;
    private $bookId;
    public $cartId;

    public $price;

    public $dateRequested = "";
    public $dateSubmitted = "";

    public $flag;

    // constructor
    public function __construct()
    {
        $this->requestId = '';
        $this->ownerId = '';
        $this->bookId = '';
        $this->cartId = '';
        $this->price = '';
        $this->dateRequested = "";
        $this->dateSubmitted = "";
        $this->flag = '';
    }

    // getter
    public function getBookId()
    {
        return $this->bookId;
    }

    // set
    public function set($requestId, $ownerId, $bookId, $cartId, $price, $dateRequested, $dateSubmitted, $flag)
    {
        $this->requestId = $requestId;
        $this->ownerId = $ownerId;
        $this->cartId = $cartId;
        $this->bookId = $bookId;
        $this->price = $price;
        $this->dateRequested = $dateRequested;
        $this->dateSubmitted = $dateSubmitted;
        $this->flag = $flag;
    }

    // fetch
    public function fetch($requestId)
    {
        global $database;

        $status = false;

        $response = $database->getReference("requests/{$requestId}")->getSnapshot()->getValue();

        if ($response) {
            $this->set($requestId, $response['owner_id'], $response['book_id'], $response['cart_id'], $response['price'], $response['date_requested'], $response['date_submitted'], $response['flag']);
            $status = true;
        }

        return $status;
    }

    // fetch all requests
    public function fetchAll()
    {
        global $database;

        $list = [];

        $response = $database->getReference("requests")->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                $list[] = $key;
            }
        }

        return $list;
    }

    public function searchById($bookId)
    {
        global $database;

        $response = $database->getReference("requests")->orderByChild('book_id')->equalTo($bookId)->getSnapshot()->getValue();

        $isRequested = $response ? true : false;

        return $isRequested;
    }

    // request 
    public function request($bookId, $cartId, $price, $userId, $requestedDate)
    {
        global $database;
        $postData = [
            'owner_id' => $userId,
            'book_id' => $bookId,
            'cart_id' => $cartId,
            'price' => $price,
            'date_requested' => $requestedDate,
            'date_submitted' => '',
            'flag' => 'pending'
        ];

        $response = $database->getReference("requests")->push($postData);

        return $response ? true : false;
    }

    // fetch users requests
    public function fetchRequestForUser($bookIdList)
    {
        global $database;

        $list = [];

        $response = $database->getReference("requests")->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if (in_array($res['book_id'], $bookIdList)) {
                    $list[] = $key;
                }
            }
        }

        return $list;
    }

    // fetch request with bookid
    public function fetchRequestWithBookId($bookId)
    {
        global $database;

        $requestId = 0;

        $response = $database->getReference("requests")->orderByChild('book_id')->equalTo($bookId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                $requestId = $key;
            }
        }

        return $requestId;
    }

    // fetch request detail with book id
    public function fetchRequestByBookId($bookId)
    {
        global $database;

        $response = $database->getReference("requests")->orderByChild('book_id')->equalTo($bookId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                $this->set($key, $res['owner_id'], $res['book_id'], $res['cart_id'], $res['price'], $res['date_requested'], $res['date_submitted'], $res['flag']);
            }
        }
    }

    // mark book as arrived || set submitted date
    public function markBookAsArrived($requestId, $currentDate)
    {
        global $database;

        $status = false;

        $response = $database->getReference("requests/{$requestId}")->getSnapshot()->getValue();

        if ($response) {
            $postData = $response;

            $postData['flag'] = 'completed';

            $postData['date_submitted'] = $currentDate;

            $response = $database->getReference("requests/{$requestId}")->update($postData);

            $status = $response ? true : false;
        }

        return $status;
    }
}