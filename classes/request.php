<?php

require_once __DIR__ . '/../app/connection.php';

class Request
{
    public $requestId;
    private $ownerId;
    private $bookId;

    public $price;

    public $date = [
        'requested' => '',
        'submitted' => ''
    ];

    public $flag;

    // constructor
    public function __construct()
    {
        $this->requestId = '';
        $this->ownerId = '';
        $this->bookId = '';
        $this->price = '';
        $this->date = [
            'requested' => '',
            'submitted' => ''
        ];
        $this->flag = '';
    }

    // getter
    public function getBookId()
    {
        return $this->bookId;
    }

    // set
    public function set($requestId, $ownerId, $bookId, $price, $date, $flag)
    {
        $this->requestId = $requestId;
        $this->ownerId = $ownerId;
        $this->bookId = $bookId;
        $this->price = $price;
        $this->date = [
            'requested' => $date['requested'],
            'submitted' => $date['submitted']
        ];
        $this->flag = $flag;
    }

    // fetch
    public function fetch($requestId)
    {
        global $database;

        $status = false;

        $response = $database->getReference("requests/{$requestId}")->getSnapshot()->getValue();

        if ($response) {
            $this->set($requestId, $response['owner_id'], $response['book_id'], $response['price'], $response['date'], $response['flag']);
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
    public function request($bookId, $price, $userId, $requestedDate)
    {
        global $database;
        $postData = [
            'owner_id' => $userId,
            'book_id' => $bookId,
            'price' => $price,
            'date' => [
                'requested' => $requestedDate,
                'submitted' => ''
            ],
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
                $this->set($key, $res['owner_id'], $res['book_id'], $res['price'], $res['date'], $res['flag']);
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

            $postData['date']['submitted'] = $currentDate;

            $response = $database->getReference("requests/{$requestId}")->update($postData);

            $status = $response ? true : false;
        }

        return $status;
    }
}