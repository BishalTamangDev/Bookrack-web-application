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
        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'admin',
            'user_id' => $userId,
            'book_id' => '',
            'type' => 'account-verification-apply',
            'date' => date('y:m:d h:i:s')
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // account verified
    public function accountVerified($userId)
    {
        global $database;
        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'book_id' => '',
            'type' => 'account-verified',
            'date' => date('y:m:d h:i:s')
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // account verified
    public function accountUnverified($userId)
    {
        global $database;
        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'user',
            'user_id' => $userId,
            'book_id' => '',
            'type' => 'account-unverified',
            'date' => date('y:m:d h:i:s')
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // new book
    public function newBook($bookId, $userId)
    {
        global $database;
        $postData = [
            'admin_id' => '',
            'cart_id' => '',
            'status' => 'unseen',
            'whose' => 'admin',
            'user_id' => $userId,
            'book_id' => $bookId,
            'type' => 'new book',
            'date' => date('y:m:d h:i:s')
        ];
        $postRef = $database->getReference("notifications")->push($postData);
        return $postRef ? true : false;
    }

    // new request
    public function cartCheckout($userId, $cartId)
    {
        global $database;
        $postData = [
            'admin_id' => '',
            'book_id' => '',
            'status' => 'unseen',
            'whose' => 'admin',
            'user_id' => $userId,
            'cart_id' => $cartId,
            'type' => 'cart checkout',
            'date' => date('y:m:d h:i:s')
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

    // fetch notification id for admin
    public function fetchAdminNotificationId()
    {
        global $database;
        $notificationIdList = [];
        $response = $database->getReference("notifications")->orderByChild('whose')->equalTo('admin')->getSnapshot()->getValue();

        if ($response)
            foreach ($response as $key => $res)
                $notificationIdList[] = $key;

        return $notificationIdList;
    }

    // fetch notification id for user
    public function fetchUserNotificationId($userId)
    {
        global $database;

        $notificationIdList = [];

        $response = $database->getReference("notifications")->orderByChild('user_id')->equalTo($userId)->getSnapshot()->getValue();

        if ($response) {
            foreach ($response as $key => $res)
                if ($res['whose'] == 'user')
                    $notificationIdList[] = $key;
        }

        return $notificationIdList;
    }

    // count adin notification
    public function countAdminNotification()
    {
        global $database;
        $response = $database->getReference("notifications")->orderByChild('whose')->equalTo('admin')->getSnapshot()->getValue();
        return count($response);
    }

    // count user notification
    public function countUserNotification($userId)
    {
        global $database;
        $count = 0;
        $list = [];
        $response = $database->getReference('notifications')->orderByChild('user_id')->equalTo($userId)->getSnapshot()->getValue();
        if ($response) {
            foreach ($response as $key => $res)
                if ($res['whose'] == 'user')
                $list[] = $key;
        }
        return sizeof($list);
    }
}