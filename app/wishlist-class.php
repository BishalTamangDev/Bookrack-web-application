<?php

use Kreait\Firebase\Database\Query\Sorter\OrderByChild;

require_once __DIR__ . '/connection.php';

class Wishlist
{
    private $userId;
    private $bookId;
    private $list = [];

    public function __construct()
    {
        $this->userId = "";
        $this->bookId = "";
        $this->list = [];
    }

    public function setWishlist($userId, $bookId, $list)
    {
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->list = $list;
    }

    // setter functions
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setList($list)
    {
        $this->list = $list;
    }

    // getter functions
    public function getUserId()
    {
        return $this->userId;
    }

    public function getList()
    {
        return $this->list;
    }

    // fetch wishlist list
    public function fetchWishlist()
    {
        global $database;
        $this->list = [];

        $response = $database->getReference("wishlists/{$this->userId}")->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $res) {
                $this->list[] = $res;
            }
        }

        return $this->list;
    }

    // fetch wishlist by userid
    public function fetchWishlistByUserId($userId)
    {
        global $database;
        $list = array();
        return $list;
    }

    // toggle wishlist
    public function toggle($bookId)
    {
        global $database;

        $status = false;

        $response = $database->getReference("wishlists/{$this->userId}")->getSnapshot()->getValue();

        if ($response) {
            $postData = [
                $bookId
            ];

            $bookExists = false;

            // fetch existing book ids
            $bookIdList = [];

            foreach ($response as $key => $res) {
                $bookIdList[] = $res;
                if ($res == $bookId)
                    $bookExists = true;
            }

            if (!$bookExists) {
                // add book
                $bookIdList[] = $bookId;
                $postData = [
                    $this->userId => $bookIdList,
                ];
                print_r($bookIdList);
                $postRef = $database->getReference("wishlists/")->update($postData);
            } else {
                // remove book
                // $bookIdList[] = $bookId;
                $key = array_search($bookId, $bookIdList);
                unset($bookIdList[$key]);

                // reindexing
                $bookIdList = array_values($bookIdList);

                $postData = [
                    $this->userId => $bookIdList,
                ];
                $postRef = $database->getReference("wishlists/")->update($postData);
            }
        } else {
            // just push data
            $postData = [
                $bookId
            ];

            try {
                $status = $database->getReference("wishlists/{$this->userId}/")->set($postData);
                $status = true;
            } catch (Exception $e) {
                $status = false;
            }
        }
        return $status;
    }

    // check wishlist
    public function check($bookId)
    {
        global $database;
        $status = false;

        $query = $database->getReference("wishlist")->orderByChild('owner_id')->equalTo($this->getUserId());
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();

        if ($response)
            $status = in_array($bookId, $response['list']) ? true : false;

        return $status;
    }
}