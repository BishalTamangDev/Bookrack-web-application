<?php

use Kreait\Firebase\Database\Query\Sorter\OrderByChild;

require_once __DIR__ . '/connection.php';

class Wishlist
{
    private $id;
    private $userId;
    private $list = [];

    public function __construct()
    {
        $this->id = "";
        $this->userId = "";
        $this->list = array();
    }

    public function setWishlist($id, $userId, $list)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->list = $list;
    }

    // setter functions
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($usreId)
    {
        $this->userId = $usreId;
    }

    public function setList($list)
    {
        $this->list = $list;
    }


    // getter functions
    public function getId()
    {
        return $this->id;
    }

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
        $list = array();

        $reference = $database->getReference("wishlist");
        $query = $reference->orderByChild('user_id')->equalTo($this->getUserId());
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();

        foreach($response as $key => $res)
            if(isset($res['list']))
                foreach($res['list'] as $listBookId)
                    $list [] = $listBookId;

        return $list;
    }

    // fetch wishlist by userid
    public function fetchWishlistByUserId($userId)
    {
        global $database;

        $list = array();



        return $list;
    }

    // add to wishlist
    public function toggle($bookId)
    {
        global $database;

        $status = false;

        $reference = $database->getReference("wishlist");
        $query = $reference->orderByChild('user_id')->equalTo($this->getUserId());
        $snapshot = $query->getSnapshot();
        $response = $snapshot->getValue();        

        if ($response) {
            $properties = [
                'user_id' =>  $this->getUserId(),
            ];

            foreach($response as $key => $res){
                if(isset($res['list']) && $res['list'] != ''){
                    if(in_array($bookId, $res['list'])){
                        // remove from wishlist
                        $newList = array();
                        
                        foreach($res['list'] as $existingBookId){
                            if($existingBookId != $bookId){
                                $newList [] = $existingBookId;
                            }
                        }
                        
                        $properties = [
                            'list' => $newList
                        ];
                    }else{
                        // add to wishlist
                        $res['list'] [] = $bookId;
                        
                        $properties = [
                            'list' => $res['list']
                        ];
                    }
                }else{
                    // add to list
                    $properties = [
                        'list' => [
                            $bookId
                        ],
                    ];
                }
            }

            try{
                $database->getReference("wishlist/{$key}")->update($properties);
                $status = true;
            }catch (Exception $e){
                $status = false;
            }
        }else{

            
            // just push data
            $properties = [
                'user_id' => $this->getUserId(),
                'list' => [
                    $bookId
                    ]
                ];

                try{
                    $status = $database->getReference("wishlist")->push($properties);
                    $status = true;
                }catch (Exception $e){
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