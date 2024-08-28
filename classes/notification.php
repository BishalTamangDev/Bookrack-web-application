<?php

require_once __DIR__ . '/../app/connection.php';
class Notification
{
    public $notificationId;
    public $adminId;
    public $userId;
    public $bookId;
    public $cartId;
    public $whose; // user || admin
    public $type; // new user || new book || new cart checkout
    public $status; // seen || unseen
    public $date; // seen || unseen

    // constructor
    public function __construct()
    {
        $this->notificationId = '';
        $this->adminId = '';
        $this->userId = '';
        $this->bookId = '';
        $this->cartId = '';
        $this->whose = '';
        $this->type = '';
        $this->date = '';
        $this->status = '';
    }

    // set
    public function set($notificationId, $type, $whose, $adminId, $userId, $bookId, $cartId, $date, $status)
    {
        $this->notificationId = $notificationId;
        $this->type = $type;
        $this->whose = $whose;
        $this->adminId = $adminId;
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->cartId = $cartId;
        $this->date = $date;
        $this->status = $status;
    }

    // account verification for admin
    public function applyForAccountVerification($userId)
    {
        global $database;

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'admin',
            'user_id' => $userId,
            'book_id' => '',
            'type' => 'account-verification-apply',
            'date' => $currentDate
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // account verified
    public function accountVerified($userId)
    {
        global $database;

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'book_id' => '',
            'type' => 'account-verified',
            'date' => $currentDate
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // account verified
    public function accountUnverified($userId)
    {
        global $database;

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'book_id' => '',
            'type' => 'account-unverified',
            'date' => $currentDate
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // new book
    public function newBook($bookId, $userId)
    {
        global $database;

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'admin',
            'user_id' => $userId,
            'book_id' => $bookId,
            'type' => 'new book',
            'date' => $currentDate
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // new request
    public function cartCheckout($userId, $cartId)
    {
        global $database;

        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'admin_id' => '',
            'book_id' => '',
            'status' => 'unseen',
            'whose' => 'admin',
            'user_id' => $userId,
            'cart_id' => $cartId,
            'type' => 'cart checkout',
            'date' => $currentDate
        ];
        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // fetch notification
    public function fetch($notificationId)
    {
        global $database;
        $status = false;
        $response = $database->getReference("notifications/{$notificationId}")->getSnapshot()->getValue();

        if ($response) {
            $this->set($notificationId, $response['type'], $response['whose'], $response['admin_id'], $response['user_id'], $response['book_id'], $response['cart_id'], $response['date'], $response['status']);
            $status = true;
        }
        return $status;
    }

    // fetch admin notification
    public function fetchAdminNotification(){
        global $database;
        $notificationList = [];
        $response = $database->getReference("notifications")->orderByChild('whose')->equalTo('admin')->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                $res ['notification_id'] = $key;
                $notificationList[] = $res;
            }
        }

        // Sorting function
        usort($notificationList, function($a, $b) {
            // Compare the dates in descending order
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $notificationList;
    }


    // fetch notification id for user
    public function fetchUserNotification($userId)
    {
        global $database;

        $notificationList = [];

        $response = $database->getReference("notifications")->orderByChild('user_id')->equalTo($userId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res) {
                if ($res['whose'] == 'user') {
                    $res ['notification_id'] = $key;
                    $notificationList[] = $res;
                }
            }
        }

        // Sorting function
        usort($notificationList, function($a, $b) {
            // Compare the dates in descending order
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $notificationList;
    }

    // count adin notification
    public function countAdminUnseenNotification()
    {
        global $database;

        $count = 0;

        $response = $database->getReference("notifications")->orderByChild('whose')->equalTo('admin')->getSnapshot()->getValue();

        foreach($response as $res) {
            if ($res['status'] == 'unseen')
                $count++;
        }
        return $count;
    }

    // count user notification
    public function countUserUnseenNotification($userId)
    {
        global $database;
        $count = 0;
        $response = $database->getReference('notifications')->orderByChild('user_id')->equalTo($userId)->getSnapshot()->getValue();
        if ($response) {
            foreach ($response as $key => $res)
                if ($res['whose'] == 'user') {
                    if($res['status'] == 'unseen')
                        $count++;                    
                }
        }
        return $count;
    }

    // request book to provider
    public function requestBook($ownerId, $bookId)
    {
        global $database;
        
        date_default_timezone_set('Asia/Kathmandu');
        $currentDate = date("Y:m:d H:i:s");

        $postData = [
            'admin_id' => '',
            'book_id' => $bookId,
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $ownerId,
            'cart_id' => '',
            'type' => 'book-request',
            'date' => $currentDate
        ];

        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // notify reader for order confirmation
    public function orderConfirmed($userId, $cartId, $currentDate)
    {
        global $database;

        $postData = [
            'admin_id' => '',
            'book_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'cart_id' => $cartId,
            'type' => 'order-confirmation',
            'date' => $currentDate
        ];

        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // notify providers as their book received
    public function bookReceived($bookId, $ownerId, $currentDate)
    {
        global $database;

        $postData = [
            'admin_id' => '',
            'book_id' => $bookId,
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $ownerId,
            'cart_id' => '',
            'type' => 'book-received',
            'date' => $currentDate
        ];

        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // notify readers as their orders has arrived
    public function orderArrived($cartId, $userId, $currentDate)
    {
        global $database;

        $postData = [
            'admin_id' => '',
            'book_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'cart_id' => $cartId,
            'type' => 'order-arrived',
            'date' => $currentDate
        ];

        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // notify readers as their order is packed
    public function orderPacked($cartId, $userId, $currentDate)
    {
        global $database;

        $postData = [
            'admin_id' => '',
            'book_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'cart_id' => $cartId,
            'type' => 'order-packed',
            'date' => $currentDate
        ];

        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // notify readers as their order is completed
    public function orderCompleted($cartId, $userId, $currentDate)
    {
        global $database;
        
        $postData = [
            'admin_id' => '',
            'book_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'cart_id' => $cartId,
            'type' => 'order-completed',
            'date' => $currentDate
        ];

        $response = $database->getReference("notifications")->push($postData);
        return $response ? true : false;
    }

    // update notification status
    public function unseenNotificationClick($id) {
        global $database;
        $postData['status'] = "seen";

        $response = $database->getReference("notifications/{$id}")->update($postData);
        return $response ? true : false;
    }
}