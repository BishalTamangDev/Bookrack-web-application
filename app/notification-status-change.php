<?php

$notificationId = $_POST['notificationId'] ?? 0;

if($notificationId == 0)
    exit;

echo $notificationId;

require_once __DIR__ . '/../classes/notification.php';

$tempNotification = new Notification();

$status = $tempNotification->unseenNotificationClick($notificationId);

echo $status;
