<?php

$count = 0;

$userId = $_POST['userId'] ?? 0;

if($userId == 0) {
    echo $count;
    exit;
}

require_once __DIR__ . '/../classes/notification.php';

$tempNotification = new Notification();

$count = $tempNotification->countUserNotification($userId);

echo $count;

exit;