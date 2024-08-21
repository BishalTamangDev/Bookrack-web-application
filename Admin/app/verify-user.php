<?php

$status = false;

$userId = $_POST['userId'] ?? 0;

if ($userId == 0) {
    echo $status;
    exit;
}

require_once __DIR__ . '/../../classes/user.php';
require_once __DIR__ . '/../../classes/notification.php';

$tempUser = new User;
$tempNotification = new Notification();

$response = $tempUser->verifyAccount($userId);

$status = $response ? true : false;

if ($status) {
    // notification
    $res = $tempNotification->accountVerified($userId);
}

echo $status;